<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_article']['fields']['bootstrap_preview_wrapper'] = array(
    'input_field_callback'    => [\Kiwi\Contao\BootstrapBundle\DataContainer\BootstrapListener::class, 'generatePreview'],
);

$GLOBALS['TL_DCA']['tl_article']['fields']['bootstrap_container_width'] = array(
    'reference'               => &$GLOBALS['TL_LANG']['bootstrap']['bootstrap_container_width_reference'],
    'inputType'               => 'radio',
    'options'                 => array(
        'container',
        'container-fluid',
    ),
    'eval'                    => array('submitOnChange' => true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(255) NOT NULL default 'container'"
);

$GLOBALS['TL_DCA']['tl_article']['fields']['bootstrap_container_breakpoint'] = array(
    'inputType'               => 'radio',
    'options'                 => [
        'sm',
        'md',
        'lg',
        'xl',
        'xxl',
    ],
    'reference'               => &$GLOBALS['TL_LANG']['bootstrap']['bootstrap_container_breakpoint_options'],
    'eval'                    => array('submitOnChange' => true, 'tl_class'=>'w50 clr'),
    'sql'                     => "varchar(255) NOT NULL default 'sm'"
);

$defaultColumns = [
    "xs"  => "1",
    "sm"  => "inherit",
    "md"  => "inherit",
    "lg"  => "inherit",
    "xl"  => "inherit",
    "xxl" => "inherit",
];

$GLOBALS['TL_DCA']['tl_article']['fields']['bootstrap_default_columns']=array(
    'exclude'                 => true,
    'default'                 => $defaultColumns,
    'input_field_callback'    => array(\Kiwi\Contao\BootstrapBundle\DataContainer\BootstrapListener::class, 'loadBreakpoints'),
    'sql'                     => "varchar(255) NULL default '".serialize($defaultColumns)."'"
);

$GLOBALS['TL_DCA']['tl_article']['fields']['bootstrap_flex_direction'] = array(
    'exclude'                 => true,
    'default'              => [
        'xs' => 'row',
        'sm' => 'inherit',
        'md' => 'inherit',
        'lg' => 'inherit',
        'xl' => 'inherit',
        'xxl' => 'inherit',
    ],
    'input_field_callback'    => array(\Kiwi\Contao\BootstrapBundle\DataContainer\BootstrapListener::class, 'loadFlexOptions'),
    'sql'                     => 'blob NULL'
);
$GLOBALS['TL_DCA']['tl_article']['fields']['bootstrap_flex_wrap'] = array(
    'exclude'                 => true,
    'default'                 => [
        'xs' => 'wrap',
        'sm' => 'inherit',
        'md' => 'inherit',
        'lg' => 'inherit',
        'xl' => 'inherit',
        'xxl' => 'inherit',
    ],
    'input_field_callback'    => array(\Kiwi\Contao\BootstrapBundle\DataContainer\BootstrapListener::class, 'loadFlexOptions'),
    'sql'                     => 'blob NULL'
);
$GLOBALS['TL_DCA']['tl_article']['fields']['bootstrap_flex_justify_content'] = array(
    'exclude'                 => true,
    'default'              => [
        'xs' => 'start',
        'sm' => 'inherit',
        'md' => 'inherit',
        'lg' => 'inherit',
        'xl' => 'inherit',
        'xxl' => 'inherit',
    ],
    'input_field_callback'    => array(\Kiwi\Contao\BootstrapBundle\DataContainer\BootstrapListener::class, 'loadFlexOptions'),
    'sql'                     => 'blob NULL'
);
$GLOBALS['TL_DCA']['tl_article']['fields']['bootstrap_flex_align_items'] = array(
    'exclude'                 => true,
    'default'              => [
        'xs' => 'stretch',
        'sm' => 'inherit',
        'md' => 'inherit',
        'lg' => 'inherit',
        'xl' => 'inherit',
        'xxl' => 'inherit',
    ],
    'input_field_callback'    => array(\Kiwi\Contao\BootstrapBundle\DataContainer\BootstrapListener::class, 'loadFlexOptions'),
    'sql'                     => 'blob NULL'
);
$GLOBALS['TL_DCA']['tl_article']['fields']['bootstrap_flex_align_content'] = array(
    'exclude'                 => true,
    'default'              => [
        'xs' => 'start',
        'sm' => 'inherit',
        'md' => 'inherit',
        'lg' => 'inherit',
        'xl' => 'inherit',
        'xxl' => 'inherit',
    ],
    'input_field_callback'    => array(\Kiwi\Contao\BootstrapBundle\DataContainer\BootstrapListener::class, 'loadFlexOptions'),
    'sql'                     => 'blob NULL'
);

PaletteManipulator::create()
    ->addLegend('bootstrap_legend', 'syndication_legend', PaletteManipulator::POSITION_BEFORE)
    ->addField([
        'bootstrap_container_width',
        'bootstrap_preview_wrapper',
        'bootstrap_default_columns',
        'bootstrap_flex_direction',
        'bootstrap_flex_wrap',
        'bootstrap_flex_justify_content',
        'bootstrap_flex_align_items',
        'bootstrap_flex_align_content'
    ], 'bootstrap_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_article')
;

$GLOBALS['TL_DCA']['tl_article']['palettes']['__selector__'][] = 'bootstrap_container_width';
$GLOBALS['TL_DCA']['tl_article']['subpalettes']['bootstrap_container_width_container'] = 'bootstrap_container_breakpoint';
