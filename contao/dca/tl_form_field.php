<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Kiwi\Contao\CmxBundle\DataContainer\PaletteManipulatorExtended;

// Description
$GLOBALS['TL_DCA']['tl_form_field']['fields']['responsiveHelpBlock'] = [
    'inputType' => 'text',
    'eval' => ['tl_class' => 'w50'],
    'sql' => "varchar(255) NOT NULL default ''"
];

PaletteManipulatorExtended::create()
    ->addField('responsiveHelpBlock','label', PaletteManipulator::POSITION_AFTER, 'type_legend')
    ->applyToAllPalettes('tl_form_field', ['default', 'explanation', 'fieldsetStart', 'fieldsetStop', 'html', 'hidden', 'submit']);



// Descriptive attribute before or after Input
$GLOBALS['TL_DCA']['tl_form_field']['fields']['responsiveInputAttribute'] = [
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'm12 w50 clr', 'submitOnChange' => true],
    'sql' => "char(1) NOT NULL default ''"
];
$GLOBALS['TL_DCA']['tl_form_field']['fields']['responsiveInputAttributeContent'] = [
    'inputType' => 'text',
    'eval' => ['tl_class' => 'w50'],
    'sql' => "varchar(255) NOT NULL default ''"
];
$GLOBALS['TL_DCA']['tl_form_field']['fields']['responsiveInputAttributePosition'] = [
    'reference' => &$GLOBALS['TL_LANG']['tl_form_field']['responsiveInputAttributePosition']['options'],
    'inputType' => 'select',
    'eval' => ['tl_class' => 'w50'],
    'options' => ['before', 'after'],
    'sql' => "varchar(16) NOT NULL default ''"
];
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['__selector__'][] = 'responsiveInputAttribute';
$GLOBALS['TL_DCA']['tl_form_field']['subpalettes']['responsiveInputAttribute'] = 'responsiveInputAttributeContent,responsiveInputAttributePosition';

PaletteManipulatorExtended::create()
    ->addField('responsiveInputAttribute', ['template_legend', 'expert_legend'], PaletteManipulator::POSITION_APPEND)
    ->applyToPalettes(['text', 'textarea', 'select'],'tl_form_field');



// Positioning next to each other
$GLOBALS['TL_DCA']['tl_form_field']['fields']['responsiveInlineOptions'] = [
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'm12 w50'],
    'sql' => "char(1) NOT NULL default ''"
];

PaletteManipulatorExtended::create()
    ->addField('responsiveInlineOptions', ['layout_legend', 'expert_legend'], PaletteManipulator::POSITION_APPEND)
    ->applyToPalettes(['radio', 'checkbox'], 'tl_form_field');