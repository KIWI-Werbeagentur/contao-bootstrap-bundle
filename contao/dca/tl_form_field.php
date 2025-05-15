<?php

// Description
$GLOBALS['TL_DCA']['tl_form_field']['fields']['responsiveHelpBlock'] = [
    'inputType' => 'text',
    'eval' => ['tl_class' => 'w50'],
    'sql' => "varchar(255) NOT NULL default ''"
];

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



// Positioning next to each other
$GLOBALS['TL_DCA']['tl_form_field']['fields']['responsiveInlineOptions'] = [
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'm12 w50'],
    'sql' => "char(1) NOT NULL default ''"
];