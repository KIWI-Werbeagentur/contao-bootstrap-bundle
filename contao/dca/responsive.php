<?php

use Contao\System;

System::loadLanguageFile('responsive');

$GLOBALS['TL_DCA']['rowCols']['fields']['responsiveRowCols'] = [
    'label' => &$GLOBALS['TL_LANG']['responsive']['responsiveRowCols'],
    'inputType' => 'responsive',
    'responsiveInputType' => 'iconedSelect',
    'eval' => ['tl_class' => "clr"],
    'options_callback' => [$GLOBALS['responsive']['config'], 'getRowCols'],
    'reference' => &$GLOBALS['TL_LANG']['responsive']['responsiveRowCols']['options'],
    'sql' => "blob NULL"
];

$GLOBALS['TL_DCA']['column']['fields']['responsiveOrder']['responsiveInputType'] = 'iconedSelect';
$GLOBALS['TL_DCA']['column']['fields']['responsiveOrder']['reference'] = &$GLOBALS['TL_LANG']['responsive']['responsiveOrder']['options'];
$GLOBALS['TL_DCA']['column']['fields']['responsiveOrder']['options'] = ['0',1,2,3,4,5,6,'first','last'];
unset($GLOBALS['TL_DCA']['column']['fields']['responsiveOrder']['eval']['rgxp']);