<?php

namespace Kiwi\Contao\BootstrapBundle\DataContainer;

use Contao\Database;
use Contao\DataContainer;
use Contao\ModuleModel;

class ModuleListener
{
    /**
     * @return array<int, string>
     */
    public function getOtherModules(DataContainer $objDca): array
    {
        $arrModules = [];
        $record = $objDca->getCurrentRecord() ?? [];
        $objModules = ModuleModel::findBy('pid', $record['pid'] ?? null);

        foreach ($objModules ?? [] as $objModule) {
            if ($objModule->id == $objDca->id || !($GLOBALS['FE_MOD']['navigationMenu'][$objModule->type] ?? false)) continue;
            $arrModules[$objModule->id] = $objModule->name . ' (ID ' . $objModule->id . ')';
        }

        return $arrModules;
    }
}
