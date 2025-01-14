<?php

use Contao\Backend;
use Contao\Controller;
use Kiwi\Contao\BootstrapBundle\DataContainer\ModuleListener;

Controller::loadDataContainer('responsive');
Backend::loadDataContainer('tl_content');
Controller::loadLanguageFile('tl_content');

unset($GLOBALS['TL_DCA']['tl_module']['fields']['responsiveColsItems']);

$GLOBALS['TL_DCA']['tl_module']['fields']['responsiveRowCols'] = $GLOBALS['TL_DCA']['rowCols']['fields']['responsiveRowCols'];


//Navbar
$GLOBALS['TL_DCA']['tl_module']['palettes']['bootstrapNavbar'] = '{title_legend},name,type;{image_legend},singleSRC,alt;{nav_legend},module;{html_legend},html,position;{template_legend:hide},customTpl;{layout_legend},breakpoint,theme;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['fields']['alt'] = [
    'inputType' => 'text',
    'eval' => ['tl_class' => 'w50', 'maxlength' => 255],
    'sql' => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['module'] = [
    'inputType' => 'select',
    'options_callback' => [ModuleListener::class, 'getOtherModules'],
    'eval' => ['mandatory' => true, 'chosen' => true, 'tl_class' => 'w50 wizard'],
    'wizard' => [['tl_content', 'editModule']],
    'sql' => "int(10) unsigned NOT NULL default '0'"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['breakpoint'] = [
    'inputType' => 'select',
    'options_callback' => [$GLOBALS['responsive']['config'], 'getBreakpoints'],
    'reference' => &$GLOBALS['TL_LANG']['responsive']['breakpoint'],
    'eval' => ['tl_class' => 'w50'],
    'sql' => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['theme'] = [
    'inputType' => 'select',
    'options' => [
        'dark',
        'light',
    ],
    'reference' => &$GLOBALS['TL_LANG']['tl_module']['theme']['options'],
    'eval' => ['includeBlankOption' => true, 'tl_class' => 'w50'],
    'sql' => "varchar(16) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['position'] = [
    'inputType' => 'select',
    'options' => [
        'out::before',
        'in::before',
        'in::after',
        'out::after',
    ],
    'reference' => &$GLOBALS['TL_LANG']['tl_module']['position']['options'],
    'eval' => ['tl_class' => 'w50'],
    'sql' => "varchar(16) NOT NULL default ''"
];
