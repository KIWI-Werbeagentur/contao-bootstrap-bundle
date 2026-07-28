<?php

namespace Kiwi\Contao\BootstrapBundle\DataContainer;

use Contao\Database;
use Contao\DataContainer;
use Contao\StringUtil;

trait AliasGeneratorTrait
{
    /**
     * @param DataContainer $objDca
     * @param string $table
     * @throws \Exception
     */
    private function generateAliasForTable(DataContainer $objDca, string $table): void
    {
        $record = $objDca->getCurrentRecord() ?? [];
        $alias = (string) ($record['alias'] ?? '');
        $autoAlias = false;

        // Generate alias if there is none
        if ($alias === '') {
            $autoAlias = true;
            $alias = StringUtil::generateAlias((string) ($record['name'] ?? ''));
        }

        $objAlias = Database::getInstance()->prepare("SELECT id FROM {$table} WHERE alias=? AND id!=?")
            ->execute($alias, $objDca->id);

        // Check whether the alias exists
        if ($objAlias->numRows) {
            if (!$autoAlias) {
                throw new \Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $alias));
            }

            $alias .= '-' . $objDca->id;
        }
        Database::getInstance()->prepare("UPDATE {$table} SET alias=? WHERE id=?")->execute($alias, $objDca->id);

        // The explicit UPDATE bypasses getCurrentRecord()'s static cache; clear it so the
        // follow-up onsubmit callback reads the new alias.
        DataContainer::clearCurrentRecordCache((int) $objDca->id, $table);
    }
}
