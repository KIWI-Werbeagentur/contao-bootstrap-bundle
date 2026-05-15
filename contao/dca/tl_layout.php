<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\System;
use Kiwi\Contao\BootstrapBundle\DataContainer\LayoutListener;

System::loadLanguageFile('responsive');

$GLOBALS['TL_DCA']['tl_layout']['config']['onsubmit_callback'][] = [LayoutListener::class, 'generateAlias'];
$GLOBALS['TL_DCA']['tl_layout']['config']['onsubmit_callback'][] = [LayoutListener::class, 'generateLayoutCustomizationFiles'];

$GLOBALS['TL_DCA']['tl_layout']['fields']['responsiveGutter'] = [
    'label' => &$GLOBALS['TL_LANG']['responsive']['responsiveGutterLayout'],
    'inputType' => 'optionalResponsive',
    'responsiveInputType' => 'select',
    'options_callback' => [$GLOBALS['responsive']['config'], 'getGutterSizeKeys'],
    'reference' => &$GLOBALS['TL_LANG']['responsive']['responsiveGutter']['options'],
    'eval' => ['tl_class' => 'clr'],
    'sql' => 'blob NULL',
];

$GLOBALS['TL_DCA']['tl_layout']['fields']['responsiveGutterHeader'] = [
    'label' => &$GLOBALS['TL_LANG']['responsive']['responsiveGutterLayoutHeader'],
    'inputType' => 'optionalResponsive',
    'responsiveInputType' => 'select',
    'options_callback' => [$GLOBALS['responsive']['config'], 'getGutterSizeKeys'],
    'reference' => &$GLOBALS['TL_LANG']['responsive']['responsiveGutter']['options'],
    'eval' => ['tl_class' => 'clr'],
    'sql' => 'blob NULL',
];

$GLOBALS['TL_DCA']['tl_layout']['fields']['responsiveGutterFooter'] = [
    'label' => &$GLOBALS['TL_LANG']['responsive']['responsiveGutterLayoutFooter'],
    'inputType' => 'optionalResponsive',
    'responsiveInputType' => 'select',
    'options_callback' => [$GLOBALS['responsive']['config'], 'getGutterSizeKeys'],
    'reference' => &$GLOBALS['TL_LANG']['responsive']['responsiveGutter']['options'],
    'eval' => ['tl_class' => 'clr'],
    'sql' => 'blob NULL',
];

foreach (['cols_1cl', 'cols_2cll', 'cols_2clr', 'cols_3cl'] as $palette) {
    $GLOBALS['TL_DCA']['tl_layout']['subpalettes'][$palette] .= ',responsiveGutter';
}

$GLOBALS['TL_DCA']['tl_layout']['subpalettes']['rows_2rwh'] .= ',responsiveGutterHeader';
$GLOBALS['TL_DCA']['tl_layout']['subpalettes']['rows_2rwf'] .= ',responsiveGutterFooter';
$GLOBALS['TL_DCA']['tl_layout']['subpalettes']['rows_3rw'] .= ',responsiveGutterHeader,responsiveGutterFooter';

$GLOBALS['TL_DCA']['tl_layout']['fields']['alias'] = [
    'exclude' => true,
    'search' => true,
    'inputType' => 'text',
    'eval' => ['readonly' => true, 'rgxp' => 'folderalias', 'doNotCopy' => true, 'maxlength' => 128, 'tl_class' => 'w50'],
    'sql' => "varchar(255) BINARY NOT NULL default ''"
];

PaletteManipulator::create()
    ->addField('alias', 'name', PaletteManipulator::POSITION_AFTER)
    ->applyToPalette('default', 'tl_layout');


$GLOBALS['TL_DCA']['tl_layout']['fields']['framework']['options'][] = 'bs_styles';
