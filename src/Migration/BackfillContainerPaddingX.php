<?php

declare(strict_types=1);

namespace Kiwi\Contao\BootstrapBundle\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;
use Kiwi\Contao\BootstrapBundle\Configuration\Grid\GridStyles;

/**
 * Back-fills `responsiveContainerPaddingX` on records that predate the container-padding-x
 * subsystem, so they keep the horizontal padding they used to render.
 *
 * Until that subsystem existed a container's own L/R padding came from Bootstrap's base
 * `.container*` rule (`padding-inline: calc(var(--bs-gutter-x) * .5)` → 12px), and `.row`
 * carried the matching negative margins. Both were deliberately dropped in favour of the
 * opt-in `.cx-*` utilities plus `column-gap`. The replacement only applies where the element
 * carries a `.cx-*` class, and that class is only rendered when the DCA field holds a value -
 * but the field's `'default' => ['xs' => 'default']` seeds newly inserted records only, so
 * every row predating the upgrade keeps its `blob NULL` and renders with no padding at all.
 *
 * What the correct value is depends on where the padding used to end up:
 *
 *   - A **bounded** container inset its content by 12px: the container padded, the row pulled
 *     back by the same amount, and the columns inside re-added it. `default` (space-3, 0.75rem)
 *     reproduces that.
 *   - A **fluid** container rendered its content flush to the viewport: the row's negative
 *     margin cancelled the container padding and nothing re-added it, because full-bleed
 *     content sits directly in the row rather than in a padded column. Its *declared* padding
 *     was 12px but its *effective* padding was zero, so `default` would newly indent it - a hero
 *     image would gain a 12px gutter it never had. Those get `space-0`.
 *
 * Fluidity is only decided where the record actually carries a container size of its own: the
 * article's `responsiveContainerSize`, and `responsiveContainer` on content elements and form
 * fields, which carry the padding field only in container mode and then are a container with
 * an inner row just like an article. Everything else keeps `default`:
 *
 *   - the layout's header/footer sections are fluid but nest an inner wrapper between the
 *     container and the row, so their content was inset by 12px like a bounded container.
 *
 * Only NULL cells are touched, so an explicit editor choice - including an explicit `space-0` -
 * is never overwritten, and the migration is idempotent.
 *
 * Deliberately limited to container padding. `responsiveRowGap` is NOT back-filled: row gap has
 * no predecessor at all, and projects upgrading into it already solve vertical spacing some
 * other way. `responsiveGutter` is NOT back-filled either: an unset gutter still falls back
 * through the `.row` class, so NULL renders correctly on its own.
 */
class BackfillContainerPaddingX extends AbstractMigration
{
    /**
     * Columns holding a container-padding-x value. Tables carrying them are discovered via the
     * schema manager, so projects adding the `containerPadding` palette fragment to further
     * tables are covered too.
     */
    private const RELEVANT_COLUMNS = [
        'responsiveContainerPaddingX',
        'responsiveContainerPaddingXHeader',
        'responsiveContainerPaddingXFooter',
    ];

    /**
     * Columns a record's own container size lives in, first match per table wins: articles use
     * `responsiveContainerSize`, content elements and form fields `responsiveContainer`.
     * Header/footer sizes are deliberately absent - see the class docblock.
     */
    private const SIZE_COLUMNS = ['responsiveContainerSize', 'responsiveContainer'];

    private const FLUID_SIZE = 'container-fluid';

    /** @var array<string, list<string>>|null table => columns with NULL cells */
    private ?array $cachedTargets = null;

    public function __construct(private readonly Connection $connection)
    {
    }

    public function getName(): string
    {
        return 'Kiwi Bootstrap: back-fill the container padding for rows predating the container-padding-x subsystem';
    }

    public function shouldRun(): bool
    {
        return $this->findTargets() !== [];
    }

    public function run(): MigrationResult
    {
        $strDefault = serialize(['xs' => GridStyles::GENERIC_DEFAULT]);
        $strNone = serialize(['xs' => 'space-0']);

        $arrTouched = [];
        $intTotal = 0;

        foreach ($this->findTargets() as $strTable => $arrColumns) {
            $strSizeColumn = $this->findSizeColumn($strTable);

            foreach ($arrColumns as $strColumn) {
                // Fluid containers first: their content used to sit flush, so they must not gain
                // the padding the bounded ones get. Only the record's own size column decides it.
                if (null !== $strSizeColumn && 'responsiveContainerPaddingX' === $strColumn) {
                    $intFluid = $this->write($strTable, $strColumn, $strNone, $strSizeColumn, self::FLUID_SIZE);

                    if ($intFluid > 0) {
                        $arrTouched[] = sprintf('%s.%s fluid→space-0 (%d)', $strTable, $strColumn, $intFluid);
                        $intTotal += $intFluid;
                    }
                }

                $intRest = $this->write($strTable, $strColumn, $strDefault);

                if ($intRest > 0) {
                    $arrTouched[] = sprintf('%s.%s →default (%d)', $strTable, $strColumn, $intRest);
                    $intTotal += $intRest;
                }
            }
        }

        // The scan described the pre-update state; drop it so a second shouldRun() in the same
        // contao:migrate pass re-reads the new one.
        $this->cachedTargets = null;

        return $this->createResult(
            true,
            sprintf(
                'Back-filled the container padding into %d row(s): %s. These rows predate the '
                . 'container-padding-x subsystem and would otherwise render without any horizontal '
                . 'container padding; fluid containers get "space-0" because the row\'s negative '
                . 'margin used to cancel their padding, so their content rendered flush. Row gap and '
                . 'gutter are intentionally left untouched.',
                $intTotal,
                $arrTouched === [] ? 'none' : implode(', ', $arrTouched),
            ),
        );
    }

    private function write(string $strTable, string $strColumn, string $strValue, ?string $strSizeColumn = null, ?string $strSize = null): int
    {
        $strSql = sprintf(
            'UPDATE %s SET %s = ? WHERE %s IS NULL',
            $this->connection->quoteIdentifier($strTable),
            $this->connection->quoteIdentifier($strColumn),
            $this->connection->quoteIdentifier($strColumn),
        );

        $arrParams = [$strValue];

        if (null !== $strSizeColumn && null !== $strSize) {
            $strSql .= sprintf(' AND %s = ?', $this->connection->quoteIdentifier($strSizeColumn));
            $arrParams[] = $strSize;
        }

        return (int) $this->connection->executeStatement($strSql, $arrParams);
    }

    /**
     * Tables and columns that still hold at least one NULL container-padding value.
     *
     * @return array<string, list<string>>
     */
    private function findTargets(): array
    {
        if ($this->cachedTargets !== null) {
            return $this->cachedTargets;
        }

        $schemaManager = $this->connection->createSchemaManager();
        $arrTargets = [];

        foreach ($schemaManager->listTableNames() as $strTable) {
            $arrColumnNames = array_map(
                static fn ($col) => $col->getName(),
                $schemaManager->listTableColumns($strTable),
            );

            foreach (array_intersect(self::RELEVANT_COLUMNS, $arrColumnNames) as $strColumn) {
                $blnHasNull = (bool) $this->connection->fetchOne(
                    sprintf(
                        'SELECT TRUE FROM %s WHERE %s IS NULL LIMIT 1',
                        $this->connection->quoteIdentifier($strTable),
                        $this->connection->quoteIdentifier($strColumn),
                    ),
                );

                if ($blnHasNull) {
                    $arrTargets[$strTable][] = $strColumn;
                }
            }
        }

        return $this->cachedTargets = $arrTargets;
    }

    private function findSizeColumn(string $strTable): ?string
    {
        $arrColumnNames = array_map(
            static fn ($col) => $col->getName(),
            $this->connection->createSchemaManager()->listTableColumns($strTable),
        );

        foreach (self::SIZE_COLUMNS as $strColumn) {
            if (\in_array($strColumn, $arrColumnNames, true)) {
                return $strColumn;
            }
        }

        return null;
    }
}
