<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_article']['config']['onload_callback'][] = [$GLOBALS['responsive'], 'getDefaults'];

$GLOBALS['TL_DCA']['tl_article']['fields']['responsiveRowCols'] = [
    'inputType' => 'responsive',
    'responsiveInputType' => 'select',
    'eval' => ['tl_class' => "clr"],
    'options_callback' => [$GLOBALS['responsive'], 'getRowCols'],
    'reference' => &$GLOBALS['TL_LANG']['MSC']['responsiveRowCols'],
    'sql' => "blob NULL"
];

PaletteManipulator::create()
    ->addField('responsiveRowCols', 'customTpl', PaletteManipulator::POSITION_AFTER)
    ->applyToPalette('default', 'tl_article');