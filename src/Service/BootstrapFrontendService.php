<?php

namespace Kiwi\Contao\BootstrapBundle\Service;

use Kiwi\Contao\ResponsiveBaseBundle\Service\ResponsiveFrontendService;

class BootstrapFrontendService extends ResponsiveFrontendService
{
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

        return array_merge($arrBootstrapClasses,parent::getAllInnerContainerClasses($arrData, $arrFields));
    }
}