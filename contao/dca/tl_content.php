<?php

use Kiwi\Contao\BootstrapBundle\DataContainer\Content;

$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = [Content::class, 'addOverwriteOption'];

// Is added to palette via onload_callback to check if parent has responsiveRowCols field
$GLOBALS['TL_DCA']['tl_content']['fields']['responsiveOverwriteRowCols'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'm12 clr', 'submitOnChange' => true],
    'sql' => "char(1) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'responsiveOverwriteRowCols';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['responsiveOverwriteRowCols'] = 'responsiveCols,responsiveOffsets';

