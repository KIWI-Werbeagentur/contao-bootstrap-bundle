<?php

namespace Kiwi\Contao\BootstrapBundle\Service;

use Kiwi\Contao\ResponsiveBaseBundle\Service\ResponsiveFrontendService;

class BootstrapFrontendService extends ResponsiveFrontendService
{
    public function getColClasses($strData, $arrData = []): array
    {
        if ($arrData['responsiveOverwriteRowCols'] ?? true) {
            return $this->getResponsiveClasses($strData, 'varColClasses');
        }
        return [];
    }

    public function getOffsetClasses($strData, $arrData = []): array
    {
        if ($arrData['responsiveOverwriteRowCols'] ?? true) {
            return $this->getResponsiveClasses($strData, 'varOffsetClasses');
        }
        return [];
    }

    public function getRowColsClasses($strData): array
    {
        return $this->getResponsiveClasses($strData, 'varRowColsClasses');
    }

    public function getAllInnerContainerClasses(array $arrData, array $arrFields = []): array
    {
        $arrBootstrapClasses = array_merge(
            [
                "row"
            ],
            $this->getRowColsClasses($arrData[$arrFields['rowCols'] ?? 'responsiveRowCols'] ?? "")
        );

        return array_merge($arrBootstrapClasses, parent::getAllInnerContainerClasses($arrData, $arrFields));
    }
}