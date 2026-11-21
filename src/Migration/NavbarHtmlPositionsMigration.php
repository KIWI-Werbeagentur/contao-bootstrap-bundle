<?php

declare(strict_types=1);

namespace Kiwi\Contao\BootstrapBundle\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Kiwi\Contao\BootstrapBundle\FrontendModule\BootstrapNavbar;

/**
 * Moves the single custom HTML of a Bootstrap navbar module (`html` plus a `position` select)
 * into the per-position field that now replaces it.
 *
 * `position` keeps its value: customized legacy templates still output `$this->html` at
 * `$this->position`, and the module fills that variable from the field for the stored position
 * ({@see BootstrapNavbar::HTML_FIELDS}). Navbars without a position never rendered their HTML
 * and are left alone.
 *
 * The new columns are added here if missing, so the move does not depend on the schema update
 * having run first.
 */
class NavbarHtmlPositionsMigration extends AbstractMigration
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function getName(): string
    {
        return 'Kiwi Bootstrap: move the navbar HTML into the field for its position';
    }

    public function shouldRun(): bool
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (!$schemaManager->tablesExist(['tl_module'])) {
            return false;
        }

        $arrColumns = array_change_key_case($schemaManager->listTableColumns('tl_module'));

        if (!isset($arrColumns['type'], $arrColumns['html'], $arrColumns['position'])) {
            return false;
        }

        return (bool) $this->connection->fetchOne(
            "SELECT COUNT(*) FROM tl_module WHERE type = 'bootstrapNavbar' AND position IN (?) AND html IS NOT NULL AND html != ''",
            [array_keys(BootstrapNavbar::HTML_FIELDS)],
            [ArrayParameterType::STRING],
        );
    }

    public function run(): MigrationResult
    {
        $arrColumns = array_change_key_case($this->connection->createSchemaManager()->listTableColumns('tl_module'));

        foreach (BootstrapNavbar::HTML_FIELDS as $strField) {
            if (!isset($arrColumns[strtolower($strField)])) {
                $this->connection->executeStatement("ALTER TABLE tl_module ADD $strField text NULL");
            }
        }

        $intRows = 0;

        foreach (BootstrapNavbar::HTML_FIELDS as $strPosition => $strField) {
            $intRows += (int) $this->connection->executeStatement(
                "UPDATE tl_module SET $strField = html, html = NULL WHERE type = 'bootstrapNavbar' AND position = ? AND html IS NOT NULL AND html != ''",
                [$strPosition],
            );
        }

        return $this->createResult(true, "Moved the HTML of $intRows navbar module(s) into the field for its position.");
    }
}
