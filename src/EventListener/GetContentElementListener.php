<?php

namespace Kiwi\Contao\BootstrapBundle\EventListener;

use Contao\ContentModel;
use Contao\Controller;
use Contao\Hybrid;
use Kiwi\Contao\BootstrapBundle\Util\BootstrapHelper;

class GetContentElementListener
{
    public function getBootstrapContentElement(ContentModel $objContentModel, string $strBuffer, object $objElement): string
    {
        Controller::loadDataContainer('tl_content');

        // Zuerst die Bootstrap-Klassen und den Element-Typ auslesen, außerdem die Klassen des Artikels/Wrappers.
        $arrColClasses = BootstrapHelper::parseBootstrapClasses($objContentModel->col);
        $strElementType = $objContentModel->type;

        // Bei Hybrid (Formulare sind im core die einzige Implementierung von Hybrid) müssen die Daten
        // nochmal explizit vom Content-Element, das sie einbindet, ausgelesen werden.
        if ($objElement instanceof Hybrid) {
            $objElement->bootstrap_headline_class = $objContentModel->bootstrap_headline_class;
            $objElement->overwriteDefaultColumns = $objContentModel->overwriteDefaultColumns;
            if ($objElement->Template) {
                $objElement->Template->overwriteDefaultColumns = $objElement->overwriteDefaultColumns;
            }
        }

        // Füge Bootstrap-Klassen zum Template hinzu, wenn die Einstellung im parent überschrieben werden soll
        if ($objElement->Template && $objContentModel->overwriteDefaultColumns && strpos($GLOBALS['TL_DCA']['tl_content']['palettes'][$strElementType], 'overwriteDefaultColumns') !== false) {
            $arrBootstrapClasses = array_values($arrColClasses?:[]);
            $strBootstrapClasses = implode(' ', $arrBootstrapClasses);
            $objElement->Template->class = trim($objElement->Template->class . ' ' . $strBootstrapClasses);
        }

        // Bei Elementen mit Überschrift wird die potenziell vorhandene Klasse ans Template übergeben.
        if ($objElement->Template) {
            if ($objElement->bootstrap_headline_class) {
                $objElement->Template->hl_class = $objElement->bootstrap_headline_class;
            }
        }

        // Aus dem geänderten Template wird $strBuffer neu generiert.
        if ($objElement->Template) {
            $strBuffer = $objElement->Template->parse();
        }

        // Zuletzt Änderungen vornehmen, die sich nur über String-Manipulation und Regex abbilden lassen.
        if ($objElement->Template && $objElement->type == 'hyperlink' && $objElement->hyperlink_button == 1) {
            // nur für legacy-Hyperlink-Templates
            $strBuffer = str_replace('class="hyperlink_', 'class="btn ' . ($objElement->hyperlinkButtonStyle ?: 'btn-primary') . ' hyperlink_', $strBuffer);
        } elseif ($objElement->type == "module") {
            // Modules are created, modified and rendered directly by \Contao\ContentModule, with no chance to intervene.
            if (isset($strBootstrapClasses) && $strBootstrapClasses) {
                $strBuffer = preg_replace('#(class="[^"]*)(")#', '\1 ' . $strBootstrapClasses . '\2', $strBuffer, 1);
            }
        }

        return $strBuffer;
    }
}
