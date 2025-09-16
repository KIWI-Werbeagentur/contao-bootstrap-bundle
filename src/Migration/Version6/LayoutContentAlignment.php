<?php

namespace Kiwi\Contao\BootstrapBundle\Migration\Version6;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;

class LayoutContentAlignment extends AbstractMigration
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getName(): string
    {
        return "BootstrapBundle: rename LayoutContentAlignment options";
    }

    public function shouldRun(): bool
    {
        $sm = $this->connection->createSchemaManager();

        if (!$sm->tablesExist('tl_content')) {
            return false;
        }

        $columns = $sm->listTableColumns('tl_content');

        if (!isset($columns['layout_content_alignment'])) {
            return false;
        }

        $stmt = $this->connection->executeQuery("SELECT COUNT(`id`) FROM `tl_content` WHERE `layout_content_alignment` = 'left' OR `layout_content_alignment` = 'right'");
        $count = $stmt->fetchOne();

        return (bool)$count;
    }

    public function run(): MigrationResult
    {
        $result = $this->connection->executeQuery("UPDATE `tl_content` SET `layout_content_alignment` = REPLACE(REPLACE(`layout_content_alignment`, 'left', 'start'), 'right', 'end') WHERE `layout_content_alignment` = 'left' OR `layout_content_alignment` = 'right'");

        if ($result->rowCount()) {
            return $this->createResult(true);
        }

        return $this->createResult(false);
    }
}
