<?php

namespace Kiwi\Contao\BootstrapBundle\Service;

use Kiwi\Contao\ResponsiveBaseBundle\Service\ResponsiveFrontendService;

class BootstrapFrontendService extends ResponsiveFrontendService
{
    public function getColClasses($strData, $varData = []): array
    {
        if (self::getProp($varData, 'responsiveOverwriteRowCols') ?: true) {
            return $this->getResponsiveClasses($strData, 'varColClasses');
        }
        return [];
    }

    public function getOffsetClasses($strData, $varData = []): array
    {
        if (self::getProp($varData, 'responsiveOverwriteRowCols') ?: true) {
            return $this->getResponsiveClasses($strData, 'varOffsetClasses');
        }
        return [];
    }

    public function getRowColsClasses($strData): array
    {
        return $this->getResponsiveClasses($strData, 'varRowColsClasses');
    }

    public function getAllInnerContainerClasses($varData, array $arrFields = []): array
    {
        $arrBootstrapClasses = array_merge(
            [
                "row"
            ],
            $this->getRowColsClasses(self::getProp($varData, $arrFields['rowCols'] ?? 'responsiveRowCols'))
        );

        return array_merge($arrBootstrapClasses, parent::getAllInnerContainerClasses($varData, $arrFields));
    }
}