<?php

namespace Kiwi\Contao\BootstrapBundle\DataContainer;

use Contao\ContentModel;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\System;

class Content
{
    #[AsCallback(table: 'tl_content', target: 'config.onload')]
    public function addOverwriteOption(DataContainer $objDca)
    {
        if (!$objDca->getActiveRecord()) return;

        $strPtable = $objDca->getActiveRecord()['ptable'];
        $strPid = $objDca->getActiveRecord()['pid'];
        $objParentModel = ($GLOBALS['TL_MODELS'][$strPtable])::findByPk($strPid);
        $arrSizes = unserialize($objParentModel->responsiveRowCols);

        if (!$arrSizes) return;

        $GLOBALS['TL_DCA']['tl_content']['fields']['responsiveOverwriteRowCols'] = [
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'm12 clr', 'submitOnChange' => true],
            'sql' => "char(1) NOT NULL default ''"
        ];

        $GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'responsiveOverwriteRowCols';
        $GLOBALS['TL_DCA']['tl_content']['subpalettes']['responsiveOverwriteRowCols'] = 'responsiveCols,responsiveOffsets';

        PaletteManipulator::create()
            ->removeField(['responsiveCols', 'responsiveOffsets'], 'template_legend')
            ->addField('responsiveOverwriteRowCols', 'customTpl', PaletteManipulator::POSITION_AFTER)
            ->applyToPalette('text', 'tl_content');


        //Set Label
        $arrSize = [];
        foreach ($arrSizes as $strBreakpoint => $strSize) {
            $intCols = 12 / intval($strSize);
            $arrSize[] = "$strBreakpoint=$intCols";
        }

        System::loadLanguageFile($strPtable);
        $strLabel = $GLOBALS['TL_LANG'][$strPtable]['responsiveRowCols'][0] ?? '';
        $GLOBALS['TL_DCA']['tl_content']['fields']['responsiveOverwriteRowCols']['label'] = $GLOBALS['TL_LANG']['tl_content']['responsiveOverwriteRowCols'];
        $GLOBALS['TL_DCA']['tl_content']['fields']['responsiveOverwriteRowCols']['label'][1] =
            sprintf(
                $GLOBALS['TL_LANG']['tl_content']['responsiveOverwriteRowCols'][1],
                "<b>" . implode("; ", $arrSize) . "</b>",
                $strLabel,
                "/contao?do=article&id=$strPid&table=$strPtable&act=edit",
                $objParentModel->title ?? ""
            );
    }
}