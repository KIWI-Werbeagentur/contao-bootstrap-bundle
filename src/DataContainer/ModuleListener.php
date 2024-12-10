<?php

namespace Kiwi\Contao\BootstrapBundle\DataContainer;

use Contao\Database;
use Contao\DataContainer;
use Contao\ModuleModel;

class ModuleListener
{
    public function getOtherModules(DataContainer $dc): array
    {
        $objModules = ModuleModel::findBy('pid', $dc->activeRecord->pid);

        while ($objModules->next()) {
            if($objModules->id == $dc->id || !($GLOBALS['FE_MOD']['navigationMenu'][$objModules->type] ?? false)) continue;
            $arrModules[$objModules->id] = $objModules->name . ' (ID ' . $objModules->id . ')';
        }

        return $arrModules;
    }
}
