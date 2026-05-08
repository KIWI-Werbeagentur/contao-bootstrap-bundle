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
     * Bootstrap responsive horizontal-gutter utilities (gx-* per breakpoint). Vertical gutters are handled separately by the bundle.
     * Map is fully enumerated in {@see \Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration}.
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

    public function getAllContainerClasses($varData, array $arrFields = [], string $table = 'tl_article'): array
    {
        $arrSpecs = [
            ['gutter', 'responsiveGutter', 'getGutterClasses'],
        ];

        $type = self::getProp($varData, 'type') ?: null;

        $arrBootstrapClasses = [];
        foreach ($arrSpecs as [$strKey, $strDefaultField, $strMethod]) {
            $strField = $arrFields[$strKey] ?? $strDefaultField;
            if (!$this->isFieldInPalette($strField, $type, $table)) {
                continue;
            }
            $arrBootstrapClasses = array_merge($arrBootstrapClasses, $this->$strMethod(self::getProp($varData, $strField)));
        }

        return array_merge(parent::getAllContainerClasses($varData, $arrFields, $table), $arrBootstrapClasses);
    }

    public function getAllInnerContainerClasses($varData, array $arrFields = [], string $table = 'tl_content'): array
    {
        $arrSpecs = [
            ['rowCols', 'responsiveRowCols', 'getRowColsClasses'],
            ['gutter',  'responsiveGutter',  'getGutterClasses'],
            ['rowGap',  'responsiveRowGap',  'getRowGapClasses'],
        ];

        $type = self::getProp($varData, 'type') ?: null;

        $arrBootstrapClasses = [];
        foreach ($arrSpecs as [$strKey, $strDefaultField, $strMethod]) {
            $strField = $arrFields[$strKey] ?? $strDefaultField;
            if (!$this->isFieldInPalette($strField, $type, $table)) {
                continue;
            }
            $arrBootstrapClasses = array_merge($arrBootstrapClasses, $this->$strMethod(self::getProp($varData, $strField)));
        }

        $arrParentClasses = parent::getAllInnerContainerClasses($varData, $arrFields, $table);

        // The structural `row` class enables the Bootstrap flex layout that makes the col-*,
        // flex-direction, justify-content, etc. utilities produced above and by the parent
        // take effect. Emit it only when at least one of those is actually being applied -
        // otherwise we would slap `.row` onto wrappers of element types that have no
        // responsive container settings at all.
        if ($arrBootstrapClasses || $arrParentClasses) {
            return array_merge(['row'], $arrBootstrapClasses, $arrParentClasses);
        }

        return [];
    }
}
