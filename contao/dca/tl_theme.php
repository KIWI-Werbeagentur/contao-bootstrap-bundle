<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Kiwi\Contao\BootstrapBundle\DataContainer\ThemeListener;

//Set default values dynamically
$GLOBALS['TL_DCA']['tl_theme']['config']['onload_callback'][] = [$GLOBALS['responsive']['config'], 'getDefaults'];


$GLOBALS['TL_DCA']['tl_theme']['fields']['responsiveBootstrapComponents'] = [
    'inputType' => 'checkbox',
    'options' => &$GLOBALS['responsive']['bootstrapComponents'],
    'reference' => &$GLOBALS['TL_LANG']['tl_theme']['bootstrapComponents']['options'],
    'eval' => ['tl_class' => 'm12 w50', 'multiple' => true],
    'sql' => "text NULL"
];

PaletteManipulator::create()
    ->addLegend('layout_legend', 'config_legend')
    ->addField('responsiveBootstrapComponents', 'layout_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_theme');


$GLOBALS['TL_DCA']['tl_theme']['config']['onsubmit_callback'][] = [ThemeListener::class, 'generateAlias'];
$GLOBALS['TL_DCA']['tl_theme']['config']['onsubmit_callback'][] = [ThemeListener::class, 'generateThemeCustomizationFile'];

Contao\CoreBundle\DataContainer\PaletteManipulator::create()
    ->addField(['alias'], 'title_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_theme');

$GLOBALS['TL_DCA']['tl_theme']['fields']['alias'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_theme']['alias'],
    'exclude' => true,
    'search' => true,
    'inputType' => 'text',
    'eval' => ['readonly' => true, 'rgxp' => 'folderalias', 'doNotCopy' => true, 'maxlength' => 128, 'tl_class' => 'w50'],
    'sql' => "varchar(255) BINARY NOT NULL default ''"
];
