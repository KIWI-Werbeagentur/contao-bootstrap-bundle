<?php

use Contao\Backend;
use Contao\Controller;
use Kiwi\Contao\BootstrapBundle\DataContainer\ModuleListener;
use Kiwi\Contao\BootstrapBundle\FrontendModule\BootstrapNavbar;

Controller::loadDataContainer('responsive');
Backend::loadDataContainer('tl_content');
Controller::loadLanguageFile('tl_content');

unset($GLOBALS['TL_DCA']['tl_module']['fields']['responsiveColsItems']);

$GLOBALS['TL_DCA']['tl_module']['fields']['responsiveRowCols'] = $GLOBALS['TL_DCA']['rowCols']['fields']['responsiveRowCols'];


//Navbar
$GLOBALS['TL_DCA']['tl_module']['palettes']['bootstrapNavbar'] = '{title_legend},name,type;{image_legend},singleSRC,imgSize,alt,inlineSvg;{nav_legend},module;{html_legend},navbarHtmlOutBefore,navbarHtmlInBefore,navbarHtmlInAfter,navbarHtmlOutAfter;{template_legend:hide},customTpl,breakpoint,animation,theme;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
$GLOBALS['TL_DCA']['tl_module']['fields']['alt'] = [
    'inputType' => 'text',
    'eval' => ['tl_class' => 'w50', 'maxlength' => 255],
    'sql' => "varchar(255) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_module']['fields']['inlineSvg'] = [
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50 m12'],
    'sql' => "char(1) NOT NULL default ''"
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

$GLOBALS['TL_DCA']['tl_module']['fields']['animation'] = [
    'inputType' => 'select',
    'options' => [0,1,2,3,4,5,6,7],
    'reference' => &$GLOBALS['TL_LANG']['burger']['animation']['options'],
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

// Replaced by the per-position HTML fields below, no longer editable. The value stays for BC:
// customized legacy templates output $this->html at $this->position, fed from the field for this position (see: BootstrapNavbar::HTML_FIELDS).
// Will be removed in v2.
$GLOBALS['TL_DCA']['tl_module']['fields']['position'] = [
    'sql' => "varchar(16) NOT NULL default ''",
];

// Custom HTML per position, matching mod_bootstrap_navbar: "Out" is outside the collapsible
// navigation container, "In" inside it. Same configuration as the core html field.
foreach (BootstrapNavbar::HTML_FIELDS as $strField) {
    $GLOBALS['TL_DCA']['tl_module']['fields'][$strField] = [
        'search' => true,
        'inputType' => 'textarea',
        'eval' => [
            'allowHtml' => true,
            'class' => 'monospace',
            'rte' => 'ace|html',
            'helpwizard' => true,
            'tl_class' => 'clr',
        ],
        'explanation' => 'insertTags',
        'sql' => "text NULL",
    ];
}
