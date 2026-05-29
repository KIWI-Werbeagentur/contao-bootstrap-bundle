<?php

namespace Kiwi\Contao\BootstrapBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;

#[AsHook('alterResponsiveValues')]
class AlterResponsiveValues
{
    public function __invoke(&$arrValues, $strMapping, $objConfig, $arrOptions)
    {
        if($strMapping == "varColClasses"){
            $strPrevValue = "";
            foreach ($objConfig->arrBreakpoints as $strBreakpoint => $strValue){
                if($arrValues[$strBreakpoint] ?? false){
                    $strPrevValue = $arrValues[$strBreakpoint];
                }
                else{
                    if($strPrevValue == 'hidden'){
                        $arrValues[$strBreakpoint] = 'hidden';
                    }
                }
            }
        }
        // Note: varContainerPaddingXClasses needs no special handling. The class
        // map has no `default` token and no per-breakpoint reset class, so the raw
        // responsive values map straight to .cx-* classes.
    }
}
