<?php

namespace Kiwi\Contao\BootstrapBundle\EventListener;

use Kiwi\Contao\BootstrapBundle\Util\BootstrapHelper;

class KiwiAddWrapperClassesListener
{
    public static function addWrapperClasses($objModule)
    {
        $arrOuterClasses = [];
        $arrInnerClasses = ['row'];
        if ($objModule->addBackgroundImage && $objModule->gridSizedBackgroundImage) {
            $arrInnerClasses[] = 'in-grid-bg';
        }
        if ($objModule->addBootstrapContainer) {
            $containerClass = $objModule->bootstrap_container_width;
            if($objModule->bootstrap_container_width == 'container' && $objModule->bootstrap_container_breakpoint) {
                $containerClass .= '-' . $objModule->bootstrap_container_breakpoint;
            }
            $arrOuterClasses[] = $containerClass;
        }
        $arrInnerClasses = array_merge($arrInnerClasses, array_values(BootstrapHelper::parseBootstrapClasses($objModule->bootstrap_default_columns, true)));
        $arrInnerClasses = array_merge($arrInnerClasses, array_values(BootstrapHelper::getBootstrapFlexClasses($objModule->id, 'tl_content', 'bootstrap_flex_direction')));
        $arrInnerClasses = array_merge($arrInnerClasses, array_values(BootstrapHelper::getBootstrapFlexClasses($objModule->id, 'tl_content', 'bootstrap_flex_wrap')));
        $arrInnerClasses = array_merge($arrInnerClasses, array_values(BootstrapHelper::getBootstrapFlexClasses($objModule->id, 'tl_content', 'bootstrap_flex_justify_content')));
        $arrInnerClasses = array_merge($arrInnerClasses, array_values(BootstrapHelper::getBootstrapFlexClasses($objModule->id, 'tl_content', 'bootstrap_flex_align_items')));
        $arrInnerClasses = array_merge($arrInnerClasses, array_values(BootstrapHelper::getBootstrapFlexClasses($objModule->id, 'tl_content', 'bootstrap_flex_align_content')));

        return [$arrOuterClasses, $arrInnerClasses];
    }
}
