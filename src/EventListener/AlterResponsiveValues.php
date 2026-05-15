<?php

namespace Kiwi\Contao\BootstrapBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration;

#[AsHook('alterResponsiveValues')]
class AlterResponsiveValues
{
    public function __invoke(&$arrValues, $strMapping, $objConfig, $arrOptions)
    {
        if ($strMapping === 'varColClasses') {
            $strPrevValue = '';
            foreach ($objConfig->arrBreakpoints as $strBreakpoint => $strValue) {
                if ($arrValues[$strBreakpoint] ?? false) {
                    $strPrevValue = $arrValues[$strBreakpoint];
                } elseif ($strPrevValue === 'hidden') {
                    $arrValues[$strBreakpoint] = 'hidden';
                }
            }
        } elseif ($strMapping === 'varGutterClasses') {
            /*
             * Per-tier `media-breakpoint-only` SCSS requires one .kiwi-gutter-x-default-{bp} class for
             * every empty breakpoint inside a "default run" (a `default` selection followed by
             * inherit-empty cells, until the next explicit gx-* size). Pure default/inherit chains
             * without a prior explicit gutter emit nothing — Bootstrap's `.row` default already applies.
             */
            $out = [];
            $inDefaultRun = false;
            $hadExplicitGutterSize = false;

            foreach ($objConfig->getBreakpoints() as $strBreakpoint) {
                $raw = $arrValues[$strBreakpoint] ?? null;
                if ($raw === '') {
                    $raw = null;
                }

                if ($raw !== null && $raw !== BootstrapConfiguration::GUTTER_DEFAULT) {
                    $out[$strBreakpoint] = (string) $raw;
                    $inDefaultRun = false;
                    $hadExplicitGutterSize = true;
                    continue;
                }

                if ($raw === BootstrapConfiguration::GUTTER_DEFAULT) {
                    $inDefaultRun = true;
                }
                if ($inDefaultRun && $hadExplicitGutterSize) {
                    $out[$strBreakpoint] = BootstrapConfiguration::GUTTER_DEFAULT;
                }
            }

            $arrValues = $out;
        }
    }
}
