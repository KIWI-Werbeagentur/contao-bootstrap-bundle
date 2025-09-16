<?php

$GLOBALS['TL_DCA']['tl_form_field']['fields']['addBootstrap'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['addBootstrap'],
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'m12 clr', 'submitOnChange'=>true),
    'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_form_field']['fields']['col']=array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['col'],
    'exclude'                 => true,
    'default'              => [
        "xs"  => "12",
        "sm"  => "inherit",
        "md"  => "inherit",
        "lg"  => "inherit",
        "xl"  => "inherit",
        "xxl" => "inherit",
    ],
    'input_field_callback'    => array(Kiwi\Contao\BootstrapBundle\DataContainer\BootstrapListener::class, 'loadBreakpoints'),
    'sql'                     => 'blob NULL'
);


$GLOBALS['TL_DCA']['tl_form_field']['fields']['bootstrapInlineOptions'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['bootstrapInlineOptions'],
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'m12 w50'),
    'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_form_field']['fields']['bootstrapInlineLabel'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['bootstrapInlineLabel'],
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'m12 w50'),
    'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_form_field']['fields']['bootstrapInputGroup'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['bootstrapInputGroup'],
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'m12 w50', 'submitOnChange'=>true),
    'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_form_field']['fields']['bootstrapInputGroupText'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['bootstrapInputGroupText'],
    'inputType'               => 'text',
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_form_field']['fields']['bootstrapInputGroupTextPosition'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['bootstrapInputGroupTextPosition'],
    'reference'               => &$GLOBALS['TL_LANG']['tl_form_field']['bootstrapInputGroupTextPositionOptions'],
    'inputType'               => 'select',
    'eval'                    => array('tl_class'=>'w50'),
    'options'                 => ['before', 'after'],
    'sql'                     => "varchar(16) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_form_field']['fields']['bootstrapButtonType'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['bootstrapButtonType'],
    'reference'               => &$GLOBALS['TL_LANG']['tl_form_field']['bootstrapButtonTypes'],
    'inputType'               => 'select',
    'eval'                    => array('tl_class'=>'w50', 'includeBlankOption' => true),
    'options'                 => [
        'btn-primary',
        'btn-primary btn-lg',
        'btn-outline-primary',
        'btn-outline-primary btn-lg',
        'btn-secondary',
        'btn-secondary btn-lg',
        'btn-outline-secondary',
        'btn-outline-secondary btn-lg',
        'btn-light',
        'btn-light btn-lg',
        'btn-outline-light',
        'btn-outline-light btn-lg',
        'btn-dark',
        'btn-dark btn-lg',
        'btn-outline-dark',
        'btn-outline-dark btn-lg',
    ],
    'sql'                     => "varchar(35) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_form_field']['fields']['bootstrapHelpBlock'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['bootstrapHelpBlock'],
    'inputType'               => 'text',
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);


$GLOBALS['TL_DCA']['tl_form_field']['palettes']['__selector__'][] = 'addBootstrap';
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['__selector__'][] = 'bootstrapInputGroup';
$GLOBALS['TL_DCA']['tl_form_field']['subpalettes']['addBootstrap'] = 'col';
$GLOBALS['TL_DCA']['tl_form_field']['subpalettes']['bootstrapInputGroup'] = 'bootstrapInputGroupText,bootstrapInputGroupTextPosition';
