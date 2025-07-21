<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Kiwi\Contao\BootstrapBundle\DataContainer\LayoutListener;

$GLOBALS['TL_DCA']['tl_layout']['config']['onsubmit_callback'][] = [LayoutListener::class, 'generateAlias'];
$GLOBALS['TL_DCA']['tl_layout']['config']['onsubmit_callback'][] = [LayoutListener::class, 'generateLayoutCustomizationFiles'];

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
