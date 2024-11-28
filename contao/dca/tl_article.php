<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_article']['config']['onload_callback'] = [[$GLOBALS['responsive'], 'getDefaults']];

$GLOBALS['TL_DCA']['tl_article']['fields']['responsiveElsPerRow'] = [
    'inputType' => 'responsive',
    'responsiveInputType' => 'select',
    'eval' => ['tl_class' => "clr"],
    'options_callback' => [$GLOBALS['responsive'], 'getElsPerRow'],
    'reference' => &$GLOBALS['TL_LANG']['MSC']['elsPerRow'],
    'sql' => "blob NULL"
];

PaletteManipulator::create()
    ->addField('responsiveElsPerRow', 'customTpl', PaletteManipulator::POSITION_AFTER)
    ->applyToPalette('default', 'tl_article');