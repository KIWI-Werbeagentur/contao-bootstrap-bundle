<?php

namespace Kiwi\Contao\BootstrapBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration;

#[AsHook('alterResponsiveValues')]
class AlterResponsiveValues
{
    /**
     * @param array<mixed> $arrValues
     * @param array<string, mixed> $arrOptions
     */
    public function __invoke(array &$arrValues, string $strMapping, BootstrapConfiguration $objConfig, array $arrOptions): void
    {
        if($strMapping == "varColClasses"){
            $strPrevValue = "";
            foreach ($objConfig->getBreakpoints() as $strBreakpoint){
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
