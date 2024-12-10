<?php

use Contao\Controller;

Controller::loadDataContainer('responsive');

unset($GLOBALS['TL_DCA']['tl_module']['fields']['responsiveColsItems']);

$GLOBALS['TL_DCA']['tl_module']['fields']['responsiveRowCols'] = $GLOBALS['TL_DCA']['rowCols']['fields']['responsiveRowCols'];