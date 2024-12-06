<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\System;

System::loadLanguageFile('responsive');

$GLOBALS['TL_DCA']['tl_article']['fields']['responsiveRowCols'] = [
    'label' => &$GLOBALS['TL_LANG']['responsive']['responsiveRowCols'],
    'inputType' => 'responsive',
    'responsiveInputType' => 'select',
    'eval' => ['tl_class' => "clr"],
    'options_callback' => [$GLOBALS['responsive']['config'], 'getRowCols'],
    'reference' => &$GLOBALS['TL_LANG']['responsive']['responsiveRowCols']['options'],
    'sql' => "blob NULL"
];

PaletteManipulator::create()
    ->addField('responsiveRowCols', 'responsiveContainerSize', PaletteManipulator::POSITION_AFTER)
    ->applyToPalette('default', 'tl_article');