<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Kiwi\Contao\BootstrapBundle\DataContainer\Content;
use Contao\System;

System::loadLanguageFile('responsive');

$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = [Content::class, 'addOverwriteOption'];

// Is added to palette via onload_callback to check if parent has responsiveRowCols field
$GLOBALS['TL_DCA']['tl_content']['fields']['responsiveOverwriteRowCols'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'm12 clr', 'submitOnChange' => true],
    'sql' => "char(1) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'responsiveOverwriteRowCols';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['responsiveOverwriteRowCols'] = 'responsiveCols,responsiveOffsets';


//CONTAINER
$GLOBALS['TL_DCA']['tl_content']['fields']['responsiveRowCols'] = [
    'label' => &$GLOBALS['TL_LANG']['responsive']['responsiveRowCols'],
    'inputType' => 'responsive',
    'responsiveInputType' => 'select',
    'eval' => ['tl_class' => "clr"],
    'options_callback' => [$GLOBALS['responsive']['config'], 'getRowCols'],
    'reference' => &$GLOBALS['TL_LANG']['responsive']['responsiveRowCols'],
    'sql' => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_content']['subpalettes']['responsiveContainer_responsiveContainerSizes'] = "responsiveRowCols," . implode(',',array_keys($GLOBALS['TL_DCA']['container']['fields'] ?? []));