<?php

namespace Kiwi\Contao\BootstrapBundle\EventListener;

use Contao\Controller;
use Contao\Widget;
use Kiwi\Contao\BootstrapBundle\Util\BootstrapHelper;

class ParseWidgetListener
{
    /**
     * @param string $strBuffer
     * @param Widget $objWidget
     *
     * @return string
     */
    public function addBootstrapClassesToWidget($strBuffer, Widget $objWidget)
    {
        if (substr($objWidget->template, 0, 5) == 'form_') {
            Controller::loadDataContainer('tl_form_field');

            if ($objWidget->addBootstrap && strpos($GLOBALS['TL_DCA']['tl_form_field']['palettes'][static::getRealWidgetType($objWidget->type)],'addBootstrap')!==false) {
                $colClasses = BootstrapHelper::parseBootstrapClasses($objWidget->col);

                $strBootstrapClasses = implode(" ", $colClasses);
                $objWidget->bootstrapClasses = $strBootstrapClasses;

                return $objWidget->inherit();
            }
        }

        return $strBuffer;
    }

    /**
     * Some widgets change their type, depending on settings.
     * E.g. text with rgxp setting "phone" changes type to "tel".
     * This method returns the original type that exists in the dca configuration.
     *
     * @param string $type
     *
     * @return string
     */
    public static function getRealWidgetType(string $type): string
    {
        switch ($type) {
            case 'password':
            case 'number':
            case 'tel':
            case 'email':
            case 'url':
                return 'text';
            default:
                return $type;
        }
    }
}
