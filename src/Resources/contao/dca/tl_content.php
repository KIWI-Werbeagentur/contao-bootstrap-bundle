<?php

use Kiwi\Contao\BootstrapBundle\DataContainer\BootstrapListener;

$GLOBALS['TL_DCA']['tl_content']['config']['oncreate_callback'][] = [\Kiwi\Contao\BootstrapBundle\DataContainer\ContentListener::class, 'onCreateUpdateLevel'];
$GLOBALS['TL_DCA']['tl_content']['config']['onsubmit_callback'][] = [\Kiwi\Contao\BootstrapBundle\DataContainer\ContentListener::class, 'onSubmitUpdateLevel'];
$GLOBALS['TL_DCA']['tl_content']['config']['ondelete_callback'][] = [\Kiwi\Contao\BootstrapBundle\DataContainer\ContentListener::class, 'onDeleteUpdateLevel'];
$GLOBALS['TL_DCA']['tl_content']['config']['oncut_callback'][] = [\Kiwi\Contao\BootstrapBundle\DataContainer\ContentListener::class, 'onCutUpdateLevel'];
$GLOBALS['TL_DCA']['tl_content']['config']['oncopy_callback'][] = [\Kiwi\Contao\BootstrapBundle\DataContainer\ContentListener::class, 'onCopyUpdateLevel'];
$GLOBALS['TL_DCA']['tl_content']['config']['onundo_callback'][] = [\Kiwi\Contao\BootstrapBundle\DataContainer\ContentListener::class, 'onUndoUpdateLevel'];

$GLOBALS['TL_DCA']['tl_content']['fields']['overwriteDefaultColumns'] = array(
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'m12', 'submitOnChange'=>true),
    'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['col']=array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['col'],
    'exclude'                 => true,
    'default'              => [
        "xs"  => "12",
        "sm"  => "inherit",
        "md"  => "inherit",
        "lg"  => "6",
        "xl"  => "inherit",
        "xxl" => "inherit",
    ],
    'input_field_callback'    => [BootstrapListener::class, 'loadBreakpoints'],
    'sql'                     => 'blob NULL'
);

$GLOBALS['TL_DCA']['tl_content']['fields']['layout_content_alignment'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['layout_content_alignment'],
	'reference'               => &$GLOBALS['TL_LANG']['tl_content']['layout_content_alignment_options'],
    'inputType'               => 'select',
    'options'                 => array('start', 'center', 'end', 'justify'),
    'eval'                    => array('tl_class'=>'w50', 'includeBlankOption'=>true),
    'sql'                     => "varchar(7) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_content']['fields']['hyperlink_button'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['hyperlink_button'],
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'m12 w50 clr', 'submitOnChange'=>true),
    'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_content']['fields']['hyperlinkButtonStyle'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['hyperlinkButtonStyle'],
    'inputType'               => 'select',
    'options'                 => [
        'btn-primary',
        'btn-secondary',
        'btn-light',
        'btn-dark',
        'btn-outline-primary',
        'btn-outline-secondary',
        'btn-outline-light',
        'btn-outline-dark',
        'btn-primary btn-lg',
        'btn-secondary btn-lg',
        'btn-light btn-lg',
        'btn-dark btn-lg',
        'btn-outline-primary btn-lg',
        'btn-outline-secondary btn-lg',
        'btn-outline-light btn-lg',
        'btn-outline-dark btn-lg',
    ],
    'reference'               => &$GLOBALS['TL_LANG']['tl_content']['hyperlinkButtonStyle_options'],
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "varchar(64) NOT NULL default ''"
);

// Extend the wrapper's capabilities
$GLOBALS['TL_DCA']['tl_content']['fields']['addBootstrapContainer'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['addBootstrapContainer'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => ['submitOnChange' => true, 'tl_class' => 'm12'],
    'sql'       => "char(1) NOT NULL default ''",
];
$GLOBALS['TL_DCA']['tl_content']['fields']['gridSizedBackgroundImage'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_content']['gridSizedBackgroundImage'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => ['submitOnChange' => true, 'tl_class' => 'w50 m12'],
    'sql'       => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_content']['fields']['wrapper_id'] = array(
    'sql'                     => "int(10) unsigned NOT NULL default 0"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['level'] = array(
    'sql'                     => "int(10) unsigned NOT NULL default 0"
);



$GLOBALS['TL_DCA']['tl_content']['fields']['bootstrap_preview'] = array(
    'input_field_callback'    => [BootstrapListener::class, 'generatePreview'],
);

$GLOBALS['TL_DCA']['tl_content']['fields']['bootstrap_preview_wrapper'] = array(
    'input_field_callback'    => [BootstrapListener::class, 'generatePreview'],
);

$GLOBALS['TL_DCA']['tl_content']['fields']['bootstrap_container_width'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['bootstrap']['bootstrap_container_width'],
    'reference'               => &$GLOBALS['TL_LANG']['bootstrap']['bootstrap_container_width_reference'],
    'inputType'               => 'radio',
    'default'                 => 'container-fluid',
    'options'                 => array(
        'container',
        'container-fluid',
    ),
    'eval'                    => array('submitOnChange' => true, 'tl_class'=>''),
    'sql'                     => "varchar(255) NOT NULL default 'container'"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['bootstrap_container_breakpoint'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['bootstrap']['bootstrap_container_breakpoint'],
    'inputType'               => 'radio',
    'options'                 => [
        'sm',
        'md',
        'lg',
        'xl',
        'xxl',
    ],
    'reference'               => &$GLOBALS['TL_LANG']['bootstrap']['bootstrap_container_breakpoint_options'],
    'eval'                    => ['submitOnChange' => true, 'tl_class'=>'clr'],
    'sql'                     => "varchar(255) NOT NULL default 'sm'"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['bootstrap_default_columns'] = [
    'label'                   => &$GLOBALS['TL_LANG']['bootstrap']['bootstrap_default_columns'],
    'exclude'                 => true,
    'default'              => [
        "xs"  => "1",
        "sm"  => "inherit",
        "md"  => "inherit",
        "lg"  => "inherit",
        "xl"  => "inherit",
        "xxl" => "inherit",
    ],
    'input_field_callback'    => [BootstrapListener::class, 'loadBreakpoints'],
    'sql'                     => 'blob NULL'
];

$GLOBALS['TL_DCA']['tl_content']['fields']['bootstrap_headline_class'] = [
    'label'                   => &$GLOBALS['TL_LANG']['bootstrap']['bootstrap_headline_class'],
    'inputType'               => 'select',
    'options'                 => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'display-1', 'display-2', 'display-3', 'display-4', 'display-5', 'display-6'],
    'reference'               => &$GLOBALS['TL_LANG']['bootstrap']['bootstrap_headline_class_options'],
    'eval'                    => ['includeBlankOption' => true, 'tl_class'=>'w50'],
    'sql'                     => ['name'=>'bootstrap_headline_class', 'type'=>'string', 'default'=>'', 'length'=>64, 'customSchemaOptions'=> ['collation'=>'ascii_bin']]
];

foreach ($GLOBALS['TL_DCA']['tl_content']['palettes'] as $palette => $strPalette) {
    if ($palette !== '__selector__' && strpos($strPalette, 'headline') !== false) {
        \Contao\CoreBundle\DataContainer\PaletteManipulator::create()
            ->addField('bootstrap_headline_class', 'headline')
            ->applyToPalette($palette, 'tl_content');
    }
}

$GLOBALS['TL_DCA']['tl_content']['fields']['bootstrap_flex_direction'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['bootstrap']['bootstrap_flex_direction'],
    'exclude'                 => true,
    'default'              => [
        'xs' => 'row',
        'sm' => 'inherit',
        'md' => 'inherit',
        'lg' => 'inherit',
        'xl' => 'inherit',
        'xxl' => 'inherit',
    ],
    'input_field_callback'    => [BootstrapListener::class, 'loadFlexOptions'],
    'sql'                     => 'blob NULL'
);
$GLOBALS['TL_DCA']['tl_content']['fields']['bootstrap_flex_wrap'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['bootstrap']['bootstrap_flex_wrap'],
    'exclude'                 => true,
    'default'              => [
        'xs' => 'wrap',
        'sm' => 'inherit',
        'md' => 'inherit',
        'lg' => 'inherit',
        'xl' => 'inherit',
        'xxl' => 'inherit',
    ],
    'input_field_callback'    => [BootstrapListener::class, 'loadFlexOptions'],
    'sql'                     => 'blob NULL'
);
$GLOBALS['TL_DCA']['tl_content']['fields']['bootstrap_flex_justify_content'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['bootstrap']['bootstrap_flex_justify_content'],
    'exclude'                 => true,
    'default'              => [
        'xs' => 'start',
        'sm' => 'inherit',
        'md' => 'inherit',
        'lg' => 'inherit',
        'xl' => 'inherit',
        'xxl' => 'inherit',
    ],
    'input_field_callback'    => [BootstrapListener::class, 'loadFlexOptions'],
    'sql'                     => 'blob NULL'
);
$GLOBALS['TL_DCA']['tl_content']['fields']['bootstrap_flex_align_items'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['bootstrap']['bootstrap_flex_align_items'],
    'exclude'                 => true,
    'default'              => [
        'xs' => 'stretch',
        'sm' => 'inherit',
        'md' => 'inherit',
        'lg' => 'inherit',
        'xl' => 'inherit',
        'xxl' => 'inherit',
    ],
    'input_field_callback'    => [BootstrapListener::class, 'loadFlexOptions'],
    'sql'                     => 'blob NULL'
);
$GLOBALS['TL_DCA']['tl_content']['fields']['bootstrap_flex_align_content'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['bootstrap']['bootstrap_flex_align_content'],
    'exclude'                 => true,
    'default'              => [
        'xs' => 'start',
        'sm' => 'inherit',
        'md' => 'inherit',
        'lg' => 'inherit',
        'xl' => 'inherit',
        'xxl' => 'inherit',
    ],
    'input_field_callback'    => [BootstrapListener::class, 'loadFlexOptions'],
    'sql'                     => 'blob NULL'
);

$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'addBootstrap';
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'overwriteDefaultColumns';
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'hyperlink_button';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['overwriteDefaultColumns'] = 'col';
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'addBootstrapContainer';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['addBootstrap'] = 'col';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['hyperlink_button'] = 'hyperlinkButtonStyle';

$GLOBALS['TL_DCA']['tl_content']['subpalettes']['addBootstrapContainer'] = 'bootstrap_preview_wrapper, bootstrap_container_width';
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'bootstrap_container_width';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['bootstrap_container_width_container'] = 'bootstrap_container_breakpoint';

$GLOBALS['TL_DCA']['tl_content']['palettes']['w-100'] = '{type_legend},type;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['fields']['playerSize']['eval']['rgxp'] = '';
array_unshift($GLOBALS['TL_DCA']['tl_content']['fields']['headline']['options'], 'div');

// element-group
$GLOBALS['TL_DCA']['tl_content']['fields']['addBackgroundImage'] = [
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => ['submitOnChange' => true, 'tl_class' => 'clr w50 m12'],
    'sql'       => "char(1) NOT NULL default ''",
];
$GLOBALS['TL_DCA']['tl_content']['fields']['singleSRC']['load_callback'][] = [BootstrapListener::class, 'setSingleSrcFlags'];
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'addBackgroundImage';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['addBackgroundImage'] = 'singleSRC,size';
