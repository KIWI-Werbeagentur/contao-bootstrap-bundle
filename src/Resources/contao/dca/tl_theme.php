<?php
$GLOBALS['TL_DCA']['tl_theme']['config']['onsubmit_callback'][]=array(\Kiwi\Contao\BootstrapBundle\DataContainer\ThemeListener::class,'generateAlias');
$GLOBALS['TL_DCA']['tl_theme']['config']['onsubmit_callback'][]=array(\Kiwi\Contao\BootstrapBundle\DataContainer\ThemeListener::class,'generateThemeCustomizationFile');

Contao\CoreBundle\DataContainer\PaletteManipulator::create()
    ->addField(array('alias'), 'title_legend', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_theme')
;

$GLOBALS['TL_DCA']['tl_theme']['fields']['alias'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_theme']['alias'],
    'exclude'                 => true,
    'search'                  => true,
    'inputType'               => 'text',
    'eval'                    => array('readonly'=>true,'rgxp'=>'folderalias', 'doNotCopy'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
    'sql'                     => "varchar(255) BINARY NOT NULL default ''"
);
