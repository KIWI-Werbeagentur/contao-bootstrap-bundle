<?php

namespace Kiwi\Contao\BootstrapBundle\DataContainer;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\Input;
use Contao\StringUtil;
use Contao\System;

class Content
{
    #[AsCallback(table: 'tl_content', target: 'config.onload')]
    public function addOverwriteOption(DataContainer $objDca): void
    {
        // The parent of the records this request is about, as resolved by core for every action: the
        // edited record's parent, or the parent whose elements are listed / edited together
        // (editAll, overrideAll). All of them share it, so one palette change fits them all. The
        // "id" parameter is not a content element in list views, so it must not be read as one.
        $strPtable = (string) $objDca->parentTable;
        $strPid = (string) $objDca->currentPid;
        $strParentModel = $GLOBALS['TL_MODELS'][$strPtable] ?? null;

        if (!$strPid || !$strParentModel) return;

        $objParentModel = $strParentModel::findByPk($strPid);

        if (!$objParentModel || !array_key_exists('responsiveRowCols', $objParentModel->row())) return;

        $arrSizes = StringUtil::deserialize($objParentModel->responsiveRowCols, true);

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
                "/contao?do=" . urlencode((string) Input::get('do')) . "&id=$strPid&table=$strPtable&act=edit",
                $objParentModel->title ?? $GLOBALS['TL_LANG']['CTE'][$objParentModel->type][0] ?? ""
            );
    }
}
