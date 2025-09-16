<?php

namespace Kiwi\Contao\BootstrapBundle\EventListener;

use Contao\Controller;
use Contao\Database;
use Contao\StringUtil;
use Contao\Template;
use Kiwi\Contao\BootstrapBundle\DataContainer\BootstrapListener;
use Kiwi\Contao\BootstrapBundle\Util\BootstrapHelper;

class ParseTemplateListener
{
    public function parseBootstrapTemplate(Template $objTemplate) {
        /**
         * mod_html is still a legacy template
         */
        if($objTemplate->getName() == 'mod_html') {
            return;
        }
        /**
         * mod_article still a legacy template
         */
        elseif (substr($objTemplate->getName(), 0, 11) == 'mod_article') {
            $arrRowCols = BootstrapHelper::parseBootstrapClasses($objTemplate->bootstrap_default_columns, true);
            if ($arrRowCols) {
                $objTemplate->rowColsClasses = join(' ', $arrRowCols);
            }

            $objTemplate->articleContainerClass = $objTemplate->bootstrap_container_width;
            if ($objTemplate->bootstrap_container_width == 'container' && $objTemplate->bootstrap_container_breakpoint) {
                $objTemplate->articleContainerClass .= '-' . $objTemplate->bootstrap_container_breakpoint;
            }

            $arrFlexClasses = [];
            $arrFlexDirection = StringUtil::deserialize($objTemplate->bootstrap_flex_direction);
            if ($arrFlexDirection) {
                $lastColumnValue = null;
                foreach ($arrFlexDirection as $breakpoint => $columnValue) {
                    if ($columnValue == 'inherit') {
                        $columnValue = $lastColumnValue;
                    }
                    if ($breakpoint == 'xs') {
                        $breakpoint = '';
                    } else {
                        $breakpoint .= '-';
                    }
                    // Klassen, die vom kleineren Breakpoint geerbt werden nicht erneut ausgeben.
                    if ($columnValue != $lastColumnValue) {
                        $arrFlexClasses[] = 'flex-' . $breakpoint . $columnValue;
                    }
                    $lastColumnValue = $columnValue;
                }
            }
            $arrFlexWrap = StringUtil::deserialize($objTemplate->bootstrap_flex_wrap);
            if ($arrFlexWrap) {
                $lastColumnValue = null;
                foreach ($arrFlexWrap as $breakpoint => $columnValue) {
                    if ($columnValue == 'inherit') {
                        $columnValue = $lastColumnValue;
                    }
                    if ($breakpoint == 'xs') {
                        $breakpoint = '';
                    } else {
                        $breakpoint .= '-';
                    }
                    // Klassen, die vom kleineren Breakpoint geerbt werden nicht erneut ausgeben.
                    if ($columnValue != $lastColumnValue) {
                        $arrFlexClasses[] = 'flex-' . $breakpoint . $columnValue;
                    }
                    $lastColumnValue = $columnValue;
                }
            }
            $arrFlexJustifyContent = StringUtil::deserialize($objTemplate->bootstrap_flex_justify_content);
            if ($arrFlexJustifyContent) {
                $lastColumnValue = null;
                foreach ($arrFlexJustifyContent as $breakpoint => $columnValue) {
                    if ($columnValue == 'inherit') {
                        $columnValue = $lastColumnValue;
                    }
                    if ($breakpoint == 'xs') {
                        $breakpoint = '';
                    } else {
                        $breakpoint .= '-';
                    }
                    // Klassen, die vom kleineren Breakpoint geerbt werden nicht erneut ausgeben.
                    if ($columnValue != $lastColumnValue) {
                        $arrFlexClasses[] = 'justify-content-' . $breakpoint . $columnValue;
                    }
                    $lastColumnValue = $columnValue;
                }
            }
            $arrFlexAlignItems = StringUtil::deserialize($objTemplate->bootstrap_flex_align_items);
            if ($arrFlexAlignItems) {
                $lastColumnValue = null;
                foreach ($arrFlexAlignItems as $breakpoint => $columnValue) {
                    if ($columnValue == 'inherit') {
                        $columnValue = $lastColumnValue;
                    }
                    if ($breakpoint == 'xs') {
                        $breakpoint = '';
                    } else {
                        $breakpoint .= '-';
                    }
                    // Klassen, die vom kleineren Breakpoint geerbt werden nicht erneut ausgeben.
                    if ($columnValue != $lastColumnValue) {
                        $arrFlexClasses[] = 'align-items-' . $breakpoint . $columnValue;
                    }
                    $lastColumnValue = $columnValue;
                }
            }
            $arrFlexAlignContent = StringUtil::deserialize($objTemplate->bootstrap_flex_align_content);
            if ($arrFlexAlignContent) {
                $lastColumnValue = null;
                foreach ($arrFlexAlignContent as $breakpoint => $columnValue) {
                    if ($columnValue == 'inherit') {
                        $columnValue = $lastColumnValue;
                    }
                    if ($breakpoint == 'xs') {
                        $breakpoint = '';
                    } else {
                        $breakpoint .= '-';
                    }
                    // Klassen, die vom kleineren Breakpoint geerbt werden nicht erneut ausgeben.
                    if ($columnValue != $lastColumnValue) {
                        $arrFlexClasses[] = 'align-content-' . $breakpoint . $columnValue;
                    }
                    $lastColumnValue = $columnValue;
                }
            }

            $objTemplate->flexClasses = join(' ', $arrFlexClasses);
        }
        if (substr($objTemplate->getName(), 0, 4) == 'mod_') {
            Controller::loadDataContainer('tl_module');

            /**
             * news is still a legacy template
             */
            if($objTemplate->newslistAddBootstrap && $objTemplate->articles) {
                $colItemClasses = BootstrapHelper::parseBootstrapClasses($objTemplate->col_item);
                $class = join(' ', $colItemClasses);

                if(is_array($objTemplate->articles)) {
                    foreach ($objTemplate->articles as $key => $value) {
                        $articles[$key] = '<div class="wrapper ' . $class . '">' . $value . '</div>';
                    }
                }
                else{
                    $articles = '<div class="wrapper ' . $class . '">' . $objTemplate->articles . '</div>';
                }
                $objTemplate->articles = $articles;
            }
            /**
             * vacancies is still a legacy template
             */
            if($objTemplate->newslistAddBootstrap && $objTemplate->vacancies) {
                $colItemClasses = BootstrapHelper::parseBootstrapClasses($objTemplate->col_item);

                $class = join(' ', $colItemClasses);
                if(is_array($objTemplate->vacancies)) {
                    foreach ($objTemplate->vacancies as $key => $value) {
                        $articles[$key] = '<div class="wrapper ' . $class . '">' . $value . '</div>';
                    }
                }
                else{
                    $articles = '<div class="wrapper ' . $class . '">' . $objTemplate->vacancies . '</div>';
                }
                $objTemplate->vacancies = $articles;

            }
            /**
             * events/calendar is still a legacy template
             */
            if($objTemplate->newslistAddBootstrap && $objTemplate->events) {
                $colItemClasses = BootstrapHelper::parseBootstrapClasses($objTemplate->col_item);

                $class = join(' ', $colItemClasses);

                $doc = new \DOMDocument();
                @$doc->loadHTML(mb_convert_encoding("<div>".$objTemplate->events."</div>", 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

                $innerHTML="";

                for ($j = 0; $j < $doc->childNodes->item(0)->childNodes->length; $j++) {
                    $currentItem = $doc->childNodes->item(0)->childNodes->item($j);
                    if ($currentItem->nodeName != "#comment" && $currentItem->nodeName != "#text") {
                        foreach ($currentItem->attributes as $name => $attrNode) {
                            if ($name == "class") {
                                $attrNode->value .= " ".$class;
                            }
                        }
                    }
                    $tmp_dom = new \DOMDocument();
                    $tmp_dom->appendChild($tmp_dom->importNode($currentItem, true));
                    $innerHTML.=$tmp_dom->saveHTML();
                }

                $objTemplate->events=$innerHTML;
            }
        }
        /**
         * fe_page is still a legacy template
         */
        elseif (substr($objTemplate->getName(), 0, 7) == 'fe_page') {
            $bootstrapClasses = [];

            $sidebars=[];
            if ($objTemplate->layout->cols == '3cl') {
                $sidebars[]="left";
                $sidebars[]="right";
            } elseif ($objTemplate->layout->cols == '2cll') {
                $sidebars[]="left";
            } elseif ($objTemplate->layout->cols == '2clr') {
                $sidebars[]="right";
            }

            $total=[];

            //Alle Breakpoints holen und nach Größe sortieren
            $arrBreakpoints = BootstrapListener::getBreakpoints();

            uasort($arrBreakpoints, function($a, $b){
                if ($a['size'] == $b['size']) {
                    return 0;
                }
                return ($a['size'] < $b['size']) ? -1 : 1;
            });

            // Standardwert für die Seitenspaltenbreite beim kleinsten Breakpoint.
            // Damit #main .col-Klassen bekommt, auch wenn es keine Seitenspalten gibt.
            $total[array_keys($arrBreakpoints)[0]] = 0;

            foreach ($sidebars as $sidebar) {
                $arrBreakpointSetting = StringUtil::deserialize($objTemplate->layout->{'col_' . $sidebar});

                $lastCol = 0;
                foreach ($arrBreakpoints as $arrBreakpoint) {
                    $col = $arrBreakpointSetting[$arrBreakpoint['name']];
                    if ($col == 'inherit') {
                        $col = $lastCol;
                    }

                    if (isset($total[$arrBreakpoint['name']])) {
                        $total[$arrBreakpoint['name']] += (int)$col;
                    } else {
                        $total[$arrBreakpoint['name']] = (int)$col;
                    }

                    if ($lastCol != $col || $col == 'none-only') {
                        if ($col == 'none-only') {
                            $base = 'd-';
                        } else {
                            $base = 'col-';
                        }
                        if ($arrBreakpoint['name'] == 'xs') {
                            $breakpoint = '';
                        } else {
                            $breakpoint = $arrBreakpoint['name'] . '-';
                        }
                        $bootstrapClasses[$sidebar][$arrBreakpoint['name']] = $base . $breakpoint . $col;
                    }
                    $lastCol = $col;
                }
            }
            $bootstrapClasses["left"][] = "order-first";

            // Jeweils mit dem vorherigen Wert vergleichen, damit Klassen, die sich vererben würden, nicht explizit gesetzt werden.
            $lastSize = null;
            foreach ($total as $breakpoint => $col) {
                $size = 12 - ($col % 12);
                if ($lastSize !== $size) {
                    if ($breakpoint == 'xs') {
                        $breakpoint = '';
                    } else {
                        $breakpoint .= '-';
                    }
                    $bootstrapClasses['main'][$breakpoint] = "col-" . $breakpoint . $size;
                }
                $lastSize = $size;
            }

            foreach ($bootstrapClasses as $column => $classes) {
                $objTemplate->{$column.'Class'} = implode(' ', $classes);
            }

            // Container-Klassen für #header, #container und #footer setzen
            $arrSections = ['header', 'container', 'footer'];
            foreach ($arrSections as $section) {
                // $property = $section.'InsideClass';
                // if ($objTemplate->layout->{'bootstrap_'.$section.'_width'} == 'container-fluid') {
                //     $property = $section.'Class';
                // }
                $objTemplate->{$section.'ContainerClass'} = $objTemplate->layout->{'bootstrap_'.$section.'_width'};
            }
        }
        /**
         * kept for BC with 4.13, custom elements and third-party bundles using legacy templates
         */
        else {
            if ($objTemplate->layout_content_alignment) {
                $alignClass = 'text-' . $objTemplate->layout_content_alignment;

                if (!in_array($alignClass, explode(' ', $objTemplate->class))) {
                    $objTemplate->class .= ' ' . $alignClass;
                }
            }
        }
    }
}
