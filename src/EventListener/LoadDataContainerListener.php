<?php

namespace Kiwi\Contao\BootstrapBundle\EventListener;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Kiwi\Contao\CmxBundle\DataContainer\PaletteManipulatorExtended;

#[AsHook('loadDataContainer', priority: -100)]
class LoadDataContainerListener
{
    public function __invoke(string $strTable): void
    {
        //add Bootstrap
        if ($strTable == 'tl_form_field') {
            PaletteManipulatorExtended::create()
                ->addField('responsiveHelpBlock', 'label', PaletteManipulator::POSITION_AFTER, 'type_legend')
                ->applyToAllPalettes('tl_form_field', ['default', 'explanation', 'fieldsetStart', 'fieldsetStop', 'html', 'hidden', 'submit']);

            PaletteManipulatorExtended::create()
                ->addField('responsiveInputAttribute', ['template_legend', 'expert_legend'], PaletteManipulator::POSITION_APPEND)
                ->applyToPalettes(['text', 'textarea', 'select'], 'tl_form_field');

            PaletteManipulatorExtended::create()
                ->addField('responsiveInlineOptions', ['layout_legend', 'expert_legend'], PaletteManipulator::POSITION_APPEND)
                ->applyToPalettes(['radio', 'checkbox'], 'tl_form_field');
        }
        if ($strTable == 'tl_module') {
            PaletteManipulatorExtended::create()
                ->addField('responsiveRowCols', 'items_legend', PaletteManipulator::POSITION_PREPEND)
                ->applyToSubpalette('addResponsiveChildren', 'tl_module');
        }
        if ($strTable == 'tl_content') {
            PaletteManipulatorExtended::create()
                ->addField('responsiveRowCols', 'items_legend', PaletteManipulator::POSITION_PREPEND)
                ->removeField('responsiveColsItems', 'default')
                ->applyToSubpalette('addResponsiveChildren', 'tl_content')
                ->applyToPalettes($GLOBALS['responsive']['tl_content']['includePalettes']['container'], 'tl_content');

        }
    }
}
