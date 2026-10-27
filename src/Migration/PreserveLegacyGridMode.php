<?php

declare(strict_types=1);

namespace Kiwi\Contao\BootstrapBundle\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;
use Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Keeps installations upgrading from a release before the marginless grid on Bootstrap's stock
 * grid, by writing `KIWI_BOOTSTRAP_LEGACY_GRID=1` to `.env.local`.
 *
 * WHY NOT MIGRATE THE DATA
 * ------------------------
 * Under the stock grid a container's padding was cancelled by the negative margin of the row
 * inside it, and the columns re-added it. How far content ended up inset therefore came from the
 * CSS, not from any setting: theme rules padding columns replaced the gutter, backgrounds reached
 * past the container padding, nested containers added no inset of their own. Whether a given
 * element was meant to sit flush or inset cannot be read from the stored records, so no
 * back-filled container padding reproduces it reliably: on upgraded sites the stock grid matched
 * the previous rendering, while every back-fill rule still left visible differences.
 *
 * {@see BackfillContainerPaddingX} still runs, so the container padding values are in place but
 * without effect until the project switches. Switching to the marginless grid is a deliberate
 * step per project, reviewed page by page: set the variable to 0 and regenerate the styles.
 *
 * HOW AN UPGRADE IS DETECTED
 * --------------------------
 * `contao:migrate` runs the migrations before the schema update. An installation whose
 * `tl_content` has the bundle's `responsiveRowCols` column (present since 1.0.0) but no
 * `responsiveContainerPaddingX` column yet is upgrading from a release before the
 * container-padding-x subsystem on this very run. Fresh installations, first-time installs of the
 * bundle on an existing Contao database (no `responsiveRowCols`) and installations already on the
 * marginless grid (`responsiveContainerPaddingX` present) are left alone, as is any explicit value
 * of the variable, `0` included.
 */
class PreserveLegacyGridMode extends AbstractMigration
{
    use WritesEnvLocalTrait;

    public function __construct(
        private readonly Connection $connection,
        private readonly Filesystem $filesystem,
        private readonly string $projectDir,
    ) {}

    public function getName(): string
    {
        return 'Kiwi Bootstrap: keep the stock Bootstrap grid for installations upgrading from before the marginless grid';
    }

    public function shouldRun(): bool
    {
        // Project has already chosen a grid - respect it.
        if (isset($_ENV[BootstrapConfiguration::LEGACY_GRID_ENV])) {
            return false;
        }

        $schemaManager = $this->connection->createSchemaManager();

        if (!$schemaManager->tablesExist(['tl_content'])) {
            return false;
        }

        $columns = array_map(
            static fn ($column) => strtolower($column->getName()),
            $schemaManager->listTableColumns('tl_content'),
        );

        // Bundle installed before (column added in 1.0.0), but not yet on the marginless grid.
        return \in_array('responsiverowcols', $columns, true)
            && !\in_array('responsivecontainerpaddingx', $columns, true);
    }

    public function run(): MigrationResult
    {
        $envFile = $this->writeEnvLocal(BootstrapConfiguration::LEGACY_GRID_ENV, '1');

        return $this->createResult(
            true,
            sprintf(
                'Upgrade from a release before the marginless grid detected. Wrote "%s=1" to %s, so the '
                . 'frontend keeps rendering Bootstrap\'s stock grid (negative row margins, column padding, '
                . 'container padding) and looks as before. To switch to the marginless grid, set it to 0, '
                . 'clear the cache and review every page.',
                BootstrapConfiguration::LEGACY_GRID_ENV,
                $envFile,
            ),
        );
    }
}
