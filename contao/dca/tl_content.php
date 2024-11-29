<?php

use Kiwi\Contao\BootstrapBundle\DataContainer\Content;

$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = [Content::class, 'addOverwriteOption'];

