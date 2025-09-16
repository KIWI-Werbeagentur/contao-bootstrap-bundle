<?php

namespace Kiwi\Contao\BootstrapBundle\DataContainer;

use Contao\Database;
use Contao\DataContainer;

class ModuleListener
{
    public function getOtherModules(DataContainer $dc): array
    {
        $arrModules = [];
        $objModules = Database::getInstance()->prepare("SELECT m.id, m.name, t.name AS theme FROM tl_module m LEFT JOIN tl_theme t ON m.pid=t.id WHERE NOT m.id = ? ORDER BY t.name, m.name")->execute($dc->id);

        while ($objModules->next()) {
            $arrModules[$objModules->theme][$objModules->id] = $objModules->name . ' (ID ' . $objModules->id . ')';
        }

        return $arrModules;
    }
}
