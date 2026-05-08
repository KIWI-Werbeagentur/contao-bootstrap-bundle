<?php

namespace Kiwi\Contao\BootstrapBundle\Service;

use Contao\Controller;
use Kiwi\Contao\ResponsiveBaseBundle\Service\ResponsiveFrontendService;

class BootstrapFrontendService extends ResponsiveFrontendService
{
    public function getColClasses($strData, $varData = []): array
    {
        if(self::propExists($varData, 'ptable')) {
            Controller::loadDataContainer(self::getProp($varData, 'ptable'));

            if(!($GLOBALS['TL_DCA'][self::getProp($varData, 'ptable')]['fields']['responsiveRowCols'] ?? false)) {
                return $this->getResponsiveClasses($strData, 'varColClasses');
            }
        }
        if (self::propExists($varData, 'responsiveOverwriteRowCols') ? self::getProp($varData, 'responsiveOverwriteRowCols') : true) {
            return $this->getResponsiveClasses($strData, 'varColClasses');
        }
        return [];
    }

    public function getOffsetClasses($strData, $varData = []): array
    {
        if(self::propExists($varData, 'ptable')) {
            Controller::loadDataContainer(self::getProp($varData, 'ptable'));

            if(!($GLOBALS['TL_DCA'][self::getProp($varData, 'ptable')]['fields']['responsiveRowCols'] ?? false)) {
                return $this->getResponsiveClasses($strData, 'varOffsetClasses');
            }
        }
        if (self::propExists($varData, 'responsiveOverwriteRowCols') ? self::getProp($varData, 'responsiveOverwriteRowCols') : true) {
            return $this->getResponsiveClasses($strData, 'varOffsetClasses');
        }
        return [];
    }

    public function getRowColsClasses($strData): array
    {
        return $this->getResponsiveClasses($strData, 'varRowColsClasses');
    }

    /**
     * Bootstrap responsive horizontal-gutter utilities (gx-* per breakpoint; «default» maps to
     * .kiwi-gutter-x-default-*). Vertical gutters are handled separately by the bundle.
     * Map is fully enumerated in {@see \Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration};
     * the {@see \Kiwi\Contao\BootstrapBundle\EventListener\AlterResponsiveValues} hook fills empty
     * breakpoints inside a default run before this runs.
     *
     * @return list<string>
     */
    public function getGutterClasses(?string $strData): array
    {
        return $this->getResponsiveClasses($strData, 'varGutterClasses');
    }

    /**
     * Bootstrap responsive row-gap utilities (row-gap-* per breakpoint). Output lands on the same
     * tag as `.row` via {@see self::getAllInnerContainerClasses()}.
     *
     * @return list<string>
     */
    public function getRowGapClasses(?string $strData): array
    {
        return $this->getResponsiveClasses($strData, 'varRowGapClasses');
    }

    public function getAllContainerClasses($varData, array $arrFields = []): array
    {
        return array_merge(
            parent::getAllContainerClasses($varData, $arrFields),
            $this->getGutterClasses(self::getProp($varData, $arrFields['gutter'] ?? 'responsiveGutter'))
        );
    }

    public function getAllInnerContainerClasses($varData, array $arrFields = []): array
    {
        $arrBootstrapClasses = array_merge(
            [
                "row"
            ],
            $this->getRowColsClasses(self::getProp($varData, $arrFields['rowCols'] ?? 'responsiveRowCols')),
            $this->getGutterClasses(self::getProp($varData, $arrFields['gutter'] ?? 'responsiveGutter')),
            $this->getRowGapClasses(self::getProp($varData, $arrFields['rowGap'] ?? 'responsiveRowGap')),
        );

        return array_merge($arrBootstrapClasses, parent::getAllInnerContainerClasses($varData, $arrFields));
    }
}
