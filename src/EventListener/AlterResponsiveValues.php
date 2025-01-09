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
    }
}
