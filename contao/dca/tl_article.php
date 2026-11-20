<?php

use Contao\Controller;
use Contao\CoreBundle\DataContainer\PaletteManipulator;

Controller::loadDataContainer('responsive');

$GLOBALS['TL_DCA']['tl_article']['fields']['responsiveRowCols'] = $GLOBALS['TL_DCA']['rowCols']['fields']['responsiveRowCols'];

// Container's own outer padding (.cx-*), detached from the generic container
// group so it does not leak into content elements / form fields. Added to the
// article palette explicitly (always visible, all container sizes). The
// frontend wrapper picks it up via getAllContainerClasses(table='tl_article'),
// whose `containerPaddingX` spec gates on this field's palette membership.
$GLOBALS['TL_DCA']['tl_article']['fields']['responsiveContainerPaddingX'] = $GLOBALS['TL_DCA']['containerPadding']['fields']['responsiveContainerPaddingX'];

PaletteManipulator::create()
    ->addField('responsiveRowCols', 'responsiveContainerSize', PaletteManipulator::POSITION_AFTER)
    ->addField('responsiveContainerPaddingX', 'responsiveRowCols', PaletteManipulator::POSITION_AFTER)
    ->applyToPalette('default', 'tl_article');