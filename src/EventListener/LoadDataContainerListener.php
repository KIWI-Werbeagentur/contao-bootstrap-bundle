<?php

namespace Kiwi\Contao\BootstrapBundle\EventListener;

use Contao\CoreBundle\DataContainer\PaletteManipulator;

class LoadDataContainerListener
{
    public function addBootstrapToDca($strName)
    {
        if ($strName == 'tl_module') {
            foreach ($GLOBALS['TL_DCA']['tl_module']['palettes'] as $key => $value) {
                //if ($key == 'newslist' || $key == 'eventlist' || $GLOBALS['TL_DCA']['tl_module']['enableBootstrapItems'][$key]) {
                if ($key == 'newslist' || $key == 'eventlist' || $key == 'vacancieslist') {
                    $GLOBALS['TL_DCA']['tl_module']['palettes'][$key] .= ';{bootstrap_wrapper_legend},addBootstrap;{bootstrap_news_legend},newslistAddBootstrap';
                } elseif ($key != 'html' && $key != 'default' && $key != '__selector__') {
                    $GLOBALS['TL_DCA']['tl_module']['palettes'][$key] .= ';{bootstrap_legend},addBootstrap';
                }
            }
        }
        elseif ($strName == 'tl_content') {
            foreach ($GLOBALS['TL_DCA']['tl_content']['palettes'] as $key => $value) {
                if ($key != 'html' && $key != 'default' && $key != '__selector__' && !preg_match("/kiwislider(.*)/", $key) && !preg_match("/(.*)Stop/", $key)) {
                    $pm = new PaletteManipulator();
                    $pm->addLegend('bootstrap_legend', 'template_legend', PaletteManipulator::POSITION_BEFORE, true);

                    $pm->addField('bootstrap_preview', 'bootstrap_legend', PaletteManipulator::POSITION_APPEND);

                    $pm->addField('overwriteDefaultColumns', 'bootstrap_legend', PaletteManipulator::POSITION_APPEND);
                    if($key!='w-100'){
                        $pm->addLegend('layout_legend', 'bootstrap_legend', PaletteManipulator::POSITION_BEFORE, true);
                        $pm->addField('layout_content_alignment', 'layout_legend', PaletteManipulator::POSITION_APPEND);
                    }
                    if($key == 'hyperlink'){
                        $pm->addField('hyperlink_button', 'layout_content_alignment');
                    } elseif ($key == 'wrapperStart' || 'element_group' === $key) {
                        $pm->addField('addBootstrapContainer', 'bootstrap_legend', PaletteManipulator::POSITION_APPEND);
                        $pm->addField('bootstrap_default_columns', 'bootstrap_legend', PaletteManipulator::POSITION_APPEND);

                        $pm->addLegend('flex_legend', 'bootstrap_legend', PaletteManipulator::POSITION_AFTER, true);

                        $pm->addField('bootstrap_flex_direction', 'flex_legend', PaletteManipulator::POSITION_APPEND);
                        $pm->addField('bootstrap_flex_wrap', 'flex_legend', PaletteManipulator::POSITION_APPEND);
                        $pm->addField('bootstrap_flex_justify_content', 'flex_legend', PaletteManipulator::POSITION_APPEND);
                        $pm->addField('bootstrap_flex_align_items', 'flex_legend', PaletteManipulator::POSITION_APPEND);
                        $pm->addField('bootstrap_flex_align_content', 'flex_legend', PaletteManipulator::POSITION_APPEND);

                        if ('element_group' === $key) {
                            $pm->addLegend('image_legend', 'type_legend');
                            $pm->addField('addBackgroundImage', 'image_legend', PaletteManipulator::POSITION_APPEND);
                        }
                    }
                    $pm->applyToPalette($key, 'tl_content');

                    if (class_exists('Kiwi\Contao\WrapperBundle\KiwiWrapperBundle') && $key == 'wrapperStart') {
                        PaletteManipulator::create()
                            ->addField('gridSizedBackgroundImage', 'image_legend')
                            ->applyToSubpalette('addBackgroundImage', 'tl_content');
                    }
                }
            }
        }
        elseif($strName == 'tl_form_field') {
            $addBootstrapPalette = PaletteManipulator::create()
                ->addLegend('bootstrap_legend', 'expert_legend', PaletteManipulator::POSITION_BEFORE)
                ->addField('addBootstrap', 'bootstrap_legend', PaletteManipulator::POSITION_APPEND);
            $helpTextPalette = PaletteManipulator::create()
                ->addField('bootstrapHelpBlock', 'label', PaletteManipulator::POSITION_AFTER, 'type_legend');
            $inputGroupPalette = PaletteManipulator::create()
                ->addField('bootstrapInputGroup', ['bootstrap_legend', 'expert_legend'], PaletteManipulator::POSITION_APPEND);
            $inlineOptionsPalette = PaletteManipulator::create()
                ->addField('bootstrapInlineOptions', ['bootstrap_legend', 'expert_legend'], PaletteManipulator::POSITION_APPEND);
            foreach ($GLOBALS['TL_DCA']['tl_form_field']['palettes'] as $key => $value) {
                if (!in_array($key, ['__selector__', 'default', 'fieldsetStop', 'html', 'hidden'])) {
                    $addBootstrapPalette->applyToPalette($key, 'tl_form_field');
                }
                // Invisible stuff and fields that don't contain inputs do not need help texts
                if (!in_array($key, ['__selector__', 'default', 'explanation', 'fieldsetStart', 'fieldsetStop', 'html', 'hidden', 'submit'])) {
                    $helpTextPalette->applyToPalette($key, 'tl_form_field');
                }
                if (in_array($key, ['text', 'textarea', 'select'])) {
                    $inputGroupPalette->applyToPalette($key, 'tl_form_field');
                }
                if (in_array($key, ['radio', 'checkbox'])) {
                    $inlineOptionsPalette->applyToPalette($key, 'tl_form_field');
                }
            }
            PaletteManipulator::create()
                ->addField('bootstrapButtonType', ['bootstrap_legend', 'expert_legend'], PaletteManipulator::POSITION_APPEND)
                ->applyToPalette('submit', 'tl_form_field');
        }
    }
}
