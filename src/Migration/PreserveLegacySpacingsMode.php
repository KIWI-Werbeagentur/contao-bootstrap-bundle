<?php

declare(strict_types=1);

namespace Kiwi\Contao\BootstrapBundle\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;
use Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration;
use Symfony\Component\Filesystem\Filesystem;

/**
 * When the bundle ships its new "no deprecated spacings by default" semantics, an
 * installation that already stores deprecated spacing values (e.g. `default`, `xxs`,
 * `gap-half`) would silently lose its BE dropdown options on upgrade and render
 * orphaned class names in the FE.
 *
     * This migration looks for any row across known responsive-spacing columns that
     * still references a deprecated key. If at least one such row is found AND the
     * project has not explicitly set `KIWI_BOOTSTRAP_DEPRECATED_SPACINGS` to `1`,
     * `2` or `3`, it writes `KIWI_BOOTSTRAP_DEPRECATED_SPACINGS=1` to `.env.local` —
     * keeping the legacy dropdowns available so the project can migrate at its own
     * pace.
     *
     * Mode `3` signals explicit opt-in to the new behaviour even with deprecated
     * data still present, so the migration respects it as a deliberate choice.
     *
     * If `.env.local` already contains the variable (e.g. set to `0`), the existing
     * line is replaced rather than duplicated.
 */
class PreserveLegacySpacingsMode extends AbstractMigration
{
    private const ENV_VAR = 'KIWI_BOOTSTRAP_DEPRECATED_SPACINGS';

    /**
     * DCA fields that hold serialized {@see ResponsiveWidget} payloads for the
     * spacing settings. Tables hosting these fields are discovered dynamically
     * via the schema manager, so projects that include the `space` /
     * `elementGroupSpace` palette fragments in additional tables are covered
     * automatically.
     */
    private const RELEVANT_COLUMNS = [
        'responsiveSpacingTop',
        'responsiveSpacingBottom',
        'responsiveGroupSpacingTop',
        'responsiveGroupSpacingBottom',
    ];

    /**
     * Cached result of {@see findFirstTableWithDeprecatedValues()}.
     *
     * The scan is expensive (full table scan + unserialize for every relevant
     * row) and {@see shouldRun()} / {@see run()} would otherwise execute it
     * twice in the same migration pass.
     */
    private ?string $cachedFoundIn = null;

    private bool $scanned = false;

    public function __construct(
        private readonly Connection $connection,
        private readonly Filesystem $filesystem,
        private readonly string $projectDir,
    ) {}

    public function getName(): string
    {
        return 'Kiwi Bootstrap: preserve legacy spacings mode for installations with stored deprecated values';
    }

    public function shouldRun(): bool
    {
        // Project has already chosen a mode — respect it.
        if ($this->hasExplicitMode()) {
            return false;
        }

        return $this->findFirstTableWithDeprecatedValues() !== null;
    }

    public function run(): MigrationResult
    {
        $envFile = $this->projectDir . '/.env.local';
        $newLine = self::ENV_VAR . '=1';

        $content = $this->filesystem->exists($envFile)
            ? (string) file_get_contents($envFile)
            : '';

        // Replace existing assignment (any value) or append at end.
        $pattern = '/^[ \t]*' . preg_quote(self::ENV_VAR, '/') . '=.*$/m';

        if (preg_match($pattern, $content) === 1) {
            $content = (string) preg_replace($pattern, $newLine, $content, 1);
        } else {
            if ($content !== '' && !str_ends_with($content, "\n")) {
                $content .= "\n";
            }
            $content .= $newLine . "\n";
        }

        $this->filesystem->dumpFile($envFile, $content);

        // Populate the in-process env so a subsequent shouldRun() call inside the
        // same `contao:migrate` invocation observes the new value. Without this,
        // Symfony's Dotenv only re-reads on the next PHP process start and the
        // migration loop would keep firing.
        $_ENV[self::ENV_VAR]    = '1';

        $foundIn = $this->findFirstTableWithDeprecatedValues();

        return $this->createResult(
            true,
            sprintf(
                'Detected stored deprecated spacing values%s. Wrote "%s" to %s so the legacy backend dropdowns stay available. Remove the variable (or set it to 0) once the data has been migrated.',
                $foundIn !== null ? sprintf(' (e.g. in table "%s")', $foundIn) : '',
                $newLine,
                $envFile,
            ),
        );
    }

    private function hasExplicitMode(): bool
    {
        $value = $_ENV[self::ENV_VAR] ?? null;

        // 1 / 2 / 3 are all explicit user intent; the migration only intervenes
        // when no mode has been chosen.
        return $value === '1' || $value === '2' || $value === '3';
    }

    /**
     * Returns the name of the first table that contains a row with at least one
     * deprecated spacing value, or null if none is found. We unserialize every
     * relevant blob rather than using a SQL LIKE prefilter because the deprecated
     * keys `xs`, `sm`, `md`, `lg`, `xl` collide with breakpoint names that
     * appear as array keys in every responsive payload — a LIKE match would
     * produce false positives for every existing row.
     *
     * The result is cached on the instance.
     */
    private function findFirstTableWithDeprecatedValues(): ?string
    {
        if ($this->scanned) {
            return $this->cachedFoundIn;
        }

        $schemaManager   = $this->connection->createSchemaManager();
        $deprecatedKeys  = array_flip(BootstrapConfiguration::DEPRECATED_SPACING_KEYS);

        foreach ($schemaManager->listTableNames() as $table) {
            $columnNames = array_map(
                static fn ($col) => $col->getName(),
                $schemaManager->listTableColumns($table),
            );

            $matchingColumns = array_values(array_intersect(self::RELEVANT_COLUMNS, $columnNames));
            if ($matchingColumns === []) {
                continue;
            }

            $quotedColumns = array_map(
                fn ($c) => $this->connection->quoteIdentifier($c),
                $matchingColumns,
            );

            $sql = sprintf(
                'SELECT %s FROM %s',
                implode(', ', $quotedColumns),
                $this->connection->quoteIdentifier($table),
            );

            $stmt = $this->connection->executeQuery($sql);
            while (($row = $stmt->fetchAssociative()) !== false) {
                foreach ($matchingColumns as $column) {
                    $blob = $row[$column] ?? null;
                    if ($blob === null || $blob === '') {
                        continue;
                    }

                    // `allowed_classes => false` guards against malicious payloads
                    // — the columns hold flat arrays of scalars, so any object
                    // would be a sign of corruption anyway.
                    $data = @unserialize((string) $blob, ['allowed_classes' => false]);
                    if (!is_array($data)) {
                        continue;
                    }

                    foreach ($data as $value) {
                        if (is_string($value) && isset($deprecatedKeys[$value])) {
                            $this->scanned       = true;
                            $this->cachedFoundIn = $table;

                            return $table;
                        }
                    }
                }
            }
        }

        $this->scanned       = true;
        $this->cachedFoundIn = null;

        return null;
    }
}
