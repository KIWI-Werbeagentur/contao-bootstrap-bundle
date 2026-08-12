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

    public function getAllInnerContainerClasses($varData, array $arrFields = [], string $table = 'tl_content', bool $skipPaletteCheck = false): array
    {
        // Checked here as well: the fields below live in the same subpalette as the parent's, so
        // they must not render either while the selector is off - and the parent call would not
        // stop the row/gutter classes added afterwards.
        if ($this->hasChildrenSettingsDisabled($varData, $table, $skipPaletteCheck)) {
            return [];
        }

        $arrBootstrapClasses = array_merge(
            [
                "row"
            ],
            $this->getRowColsClasses(self::getProp($varData, $arrFields['rowCols'] ?? 'responsiveRowCols'))
        );

        return array_merge($arrBootstrapClasses, parent::getAllInnerContainerClasses($varData, $arrFields, $table, $skipPaletteCheck));
    }
}
