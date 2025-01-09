<?php

namespace Kiwi\Contao\BootstrapBundle\DataContainer;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\StringUtil;
use Contao\System;

class Content
{
    #[AsCallback(table: 'tl_content', target: 'config.onload')]
    public function addOverwriteOption(DataContainer $objDca)
    {
        if (!$objDca->getCurrentRecord()) return;

        $strPtable = $objDca->getCurrentRecord()['ptable'];
        $strPid = $objDca->getCurrentRecord()['pid'];
        $objParentModel = ($GLOBALS['TL_MODELS'][$strPtable])::findByPk($strPid);
        $arrSizes = StringUtil::deserialize($objParentModel->responsiveRowCols);

        if (!$arrSizes) return;

        // Rearrange palette, when parent has row-cols set
        foreach ($GLOBALS['TL_DCA'][$objDca->table]['palettes'] as $strPalette => $varFields) {
            if (!is_string($varFields) || in_array($strPalette, ($GLOBALS['responsive'][$objDca->table]['excludePalettes']['column'] ?? []))) continue;

            PaletteManipulator::create()
                ->removeField(['responsiveCols', 'responsiveOffsets'], 'layout_legend')
                ->addField('responsiveOverwriteRowCols', 'layout_legend', PaletteManipulator::POSITION_PREPEND)
                ->applyToPalette($strPalette, $objDca->table);
        }

        // Set Label dynamically to show parent settings
        $arrSize = [];
        foreach ($arrSizes as $strBreakpoint => $strSize) {
            $intCols = is_numeric($strSize) ? 12 / intval($strSize) : $strSize;
            $arrSize[] = "$strBreakpoint=$intCols";
        }

        System::loadLanguageFile($strPtable);
        $strLabel = $GLOBALS['TL_DCA'][$objDca->table]['fields']['responsiveRowCols']['reference'][0] ?? '';
        $GLOBALS['TL_DCA']['tl_content']['fields']['responsiveOverwriteRowCols']['label'] = $GLOBALS['TL_LANG']['tl_content']['responsiveOverwriteRowCols'];
        $GLOBALS['TL_DCA']['tl_content']['fields']['responsiveOverwriteRowCols']['label'][1] =
            sprintf(
                $GLOBALS['TL_LANG']['tl_content']['responsiveOverwriteRowCols'][1],
                "<b>" . implode("; ", $arrSize) . "</b>",
                $strLabel,
                "/contao?do=article&id=$strPid&table=$strPtable&act=edit",
                $objParentModel->title ?? $GLOBALS['TL_LANG']['CTE'][$objParentModel->type][0] ?? ""
            );
    }
}