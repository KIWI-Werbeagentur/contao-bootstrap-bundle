<?php

$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'addBootstrap';
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'newslistAddBootstrap';

$GLOBALS['TL_DCA']['tl_module']['subpalettes']['addBootstrap'] = 'bootstrap_preview,col_list';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['newslistAddBootstrap'] = 'col_item';

$GLOBALS['TL_DCA']['tl_module']['fields']['addBootstrap'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['addBootstrap'],
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'m12 w50', 'submitOnChange'=>true),
    'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['newslistAddBootstrap'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['addBootstrap'],
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'m12 w50', 'submitOnChange'=>true),
    'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['bootstrap_preview'] = array(
    'input_field_callback'    => [\Kiwi\Contao\BootstrapBundle\DataContainer\BootstrapListener::class, 'generatePreview'],
);

foreach(['list','item'] as $part){
    $GLOBALS['TL_DCA']['tl_module']['fields']['col_'.$part]=array(
        'label'                   => &$GLOBALS['TL_LANG']['tl_module']['col_'.$part],
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
}

\Contao\Backend::loadDataContainer('tl_content');
\Contao\Controller::loadLanguageFile('tl_content');

// BootstrapNavbar
$GLOBALS['TL_DCA']['tl_module']['palettes']['bootstrapNavbar'] = '{title_legend},name,type;{image_legend},singleSRC,altText;{layout_legend},navbarExpandBreakpoint,navbarTheme;{nav_legend},navigationModule;{kiwi_htmlExtension_legend},html,kiwi_htmlExtensionPosition;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['fields']['altText'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['altText'],
    'inputType'               => 'text',
    'eval'                    => array('tl_class'=>'w50', 'maxlength' => 255),
    'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['navigationModule'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['navigationModule'],
    'inputType'               => 'select',
    'options_callback'        => [\Kiwi\Contao\BootstrapBundle\DataContainer\ModuleListener::class, 'getOtherModules'],
    'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'tl_class'=>'w50 wizard'),
    'wizard' => array
    (
        array('tl_content', 'editModule')
    ),
    'sql'                     => "int(10) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['navbarExpandBreakpoint'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['navbarExpandBreakpoint'],
    'inputType'               => 'select',
    'options_callback'        => function() {
        $arrOptions = array_column(Kiwi\Contao\BootstrapBundle\DataContainer\BootstrapListener::getBreakpoints(), 'name');

        return array_values(array_filter($arrOptions, function($val) {
            return $val !== 'xs';
        }));
    },
    'eval'                    => array('tl_class'=>'w50', 'includeBlankOption' => true),
    'sql'                     => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['navbarTheme'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['navbarTheme'],
    'inputType'               => 'select',
    'options'                 => array(
        'dark',
        'light',
    ),
    'reference'               => &$GLOBALS['TL_LANG']['tl_module']['navbarTheme_options'],
    'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(16) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['kiwi_htmlExtensionPosition'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['kiwi_htmlExtensionPosition'],
    'inputType'               => 'select',
    'options'                 => array(
        'out-before',
        'in-before',
        'in-after',
        'out-after',
    ),
    'reference'               => &$GLOBALS['TL_LANG']['tl_module']['kiwi_htmlExtensionPosition_options'],
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "varchar(16) NOT NULL default ''"
);
