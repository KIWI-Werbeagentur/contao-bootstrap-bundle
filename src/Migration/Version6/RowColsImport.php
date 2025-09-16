<?php

namespace Kiwi\Contao\BootstrapBundle\Migration\Version6;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Contao\LayoutModel;
use Contao\ThemeModel;
use Doctrine\DBAL\Connection;
use Symfony\Component\Filesystem\Filesystem;

class RowColsImport extends AbstractMigration
{
    private Connection $connection;

    private Filesystem $filesystem;

    private string $targetPath = 'files/themes/';

    private string $importLine = '@import "../../../../vendor/kiwi/contao-bootstrap-bundle/src/Resources/customization/row-cols-import.scss";';

    public function __construct(Connection $connection, Filesystem $filesystem)
    {
        $this->connection = $connection;
        $this->filesystem = $filesystem;
    }

    public function getName(): string
    {
        return 'BootstrapBundle Row Cols Import';
    }

    public function shouldRun(): bool
    {
        $sm = $this->connection->createSchemaManager();
        if (!$sm->tablesExist(['tl_theme', 'tl_layout'])) {
            return false;
        }

        $count = 0;
        foreach (ThemeModel::findAll(['return' => 'Array']) as $objTheme) {
            foreach (LayoutModel::findAll(['return' => 'Array']) as $objLayout) {
                $filePath = $this->targetPath . $objTheme->alias . '/' . $objLayout->alias . '/_imports-' . $objLayout->alias . '.scss';
                if ($this->filesystem->exists($filePath)) {
                    $lines = file($filePath, FILE_IGNORE_NEW_LINES);
                    if (!in_array($this->importLine, $lines)) {
                        $count++;
                    }
                }
            }
        }
        if ($count > 0) {
            return true;
        }

        return false;
    }

    public function run(): MigrationResult
    {
        foreach (ThemeModel::findAll(['return' => 'Array']) as $objTheme) {
            foreach (LayoutModel::findAll(['return' => 'Array']) as $objLayout) {
                $filePath = $this->targetPath . $objTheme->alias . '/' . $objLayout->alias . '/_imports-' . $objLayout->alias . '.scss';
                if ($this->filesystem->exists($filePath)) {
                    $lines = file($filePath, FILE_IGNORE_NEW_LINES);
                    if (!in_array($this->importLine, $lines)) {
                        $importLineIndex = array_search('@import "layoutvars-' . $objLayout->alias . '";', $lines);
                        if ($importLineIndex !== false) {
                            array_splice($lines, $importLineIndex + 1, 0, $this->importLine);
                            file_put_contents($filePath, implode("\n", $lines));
                        }
                    }
                }
            }
        }

        return $this->createResult(true);
    }
}
