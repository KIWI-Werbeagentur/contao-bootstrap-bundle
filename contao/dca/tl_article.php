<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_article']['fields']['responsiveElsPerRow'] = [
    'inputType' => 'responsive',
    'responsiveInputType' => 'select',
    'eval' => ['tl_class' => "clr"],
    'options_callback' => [$GLOBALS['responsive'], 'getElsPerRow'],
    'reference' => &$GLOBALS['TL_LANG']['MSC']['elsPerRow'],
    'default' => ['xs' => 1, 'lg' => 2],
    'sql' => "blob NULL"
];

PaletteManipulator::create()
    ->addField('responsiveElsPerRow', 'template_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_article');