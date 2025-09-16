<?php

namespace Kiwi\Contao\BootstrapBundle\EventListener;

use Contao\Controller;
use Contao\Hybrid;
use Contao\ModuleModel;
use Kiwi\Contao\BootstrapBundle\Util\BootstrapHelper;

class GetFrontendModuleListener
{
    public function getBootstrapFrontendModule(ModuleModel $objModuleModel, string $strBuffer, object $objModule): string
    {
        Controller::loadDataContainer('tl_module');

        // Zuerst die Bootstrap-Klassen und den Modul-Typ auslesen.
        $arrColClasses = BootstrapHelper::parseBootstrapClasses($objModule->col_list);
        $strModuleType = $objModule->type;

        // Bei Hybrid (Formulare sind im core die einzige Implementierung von Hybrid) müssen die Daten
        // nochmal explizit vom Modul, das sie einbindet, ausgelesen werden.
        if ($objModule instanceof Hybrid) {
            $objModule->addBootstrap = $objModule->getParent()->addBootstrap;
            if ($objModule->Template) {
                $objModule->Template->addBootstrap = $objModule->addBootstrap;
            }
            $arrColClasses = BootstrapHelper::parseBootstrapClasses($objModule->getParent()->col_list);
            $strModuleType = $objModule->getParent()->type;

        }

        if ($objModule->addBootstrap && strpos($GLOBALS['TL_DCA']['tl_module']['palettes'][$strModuleType], 'addBootstrap') !== false) {
            $arrBootstrapClasses = array_values($arrColClasses);
            $strBootstrapClasses = implode(' ', $arrBootstrapClasses);

            if ($strBootstrapClasses) {
                if ($objModule->Template) {
                    $objModule->Template->class = trim($objModule->Template->class . ' ' . $strBootstrapClasses);
                    $strBuffer = $objModule->Template->parse();
                }
            }
        }

        return $strBuffer;
    }
}
