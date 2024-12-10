<?php

use Contao\Controller;
use Contao\CoreBundle\DataContainer\PaletteManipulator;

Controller::loadDataContainer('responsive');

$GLOBALS['TL_DCA']['tl_article']['fields']['responsiveRowCols'] = $GLOBALS['TL_DCA']['rowCols']['fields']['responsiveRowCols'];

PaletteManipulator::create()
    ->addField('responsiveRowCols', 'responsiveContainerSize', PaletteManipulator::POSITION_AFTER)
    ->applyToPalette('default', 'tl_article');