<?php

namespace Kiwi\Contao\BootstrapBundle\Util;

use Contao\Database;
use Contao\StringUtil;
use Contao\Widget;

class BootstrapHelper
{
    public static function parseBootstrapClasses(?string $serializedArray, bool $useRowCols = false): array
    {
        $arrColumns = [];
        $prevColumns = null;

        foreach (StringUtil::deserialize($serializedArray, true) as $key => $columns) {
            if ($columns == 'inherit' && !is_numeric($prevColumns) && $prevColumns != 'auto') {
                $columns = $prevColumns;
            }
            $prevColumns = $columns;

            // Klassen, die vom kleineren Breakpoint geerbt werden nicht erneut ausgeben.
            if ($columns == 'inherit' || $columns == 'umbruch') {
                continue;
            }

            if ($useRowCols) {
                $base = 'row-cols-';
            } elseif ($columns == 'none-only') {
                $base = 'd-';
            } else {
                $base = 'col-';
            }
            $breakpoint = $key . '-';
            if ($key == 'xs') {
                $breakpoint = '';
            }
            $arrColumns[$key] = $base . $breakpoint . $columns;
        }

        return $arrColumns;
    }

    public static function getBootstrapFlexClasses($currentID, $strTable, $currentField) {
        $columnsQuery=Database::getInstance()->prepare("SELECT ".$currentField." FROM ".$strTable." WHERE id=?")->execute($currentID)->{$currentField};
        $arrFlexClasses = [];
        $lastValue = null;
        if ($columnsQuery) {
            foreach (StringUtil::deserialize($columnsQuery) as $key => $value) {
                if ($value == 'inherit' && !is_numeric($lastValue)) {
                    $value = $lastValue;
                }

                $breakpoint = $key . '-';
                if ($key == 'xs') {
                    $breakpoint = '';
                }

                if ($currentField == 'bootstrap_flex_justify_content') {
                    $base = 'justify-content-';
                } else if ($currentField == 'bootstrap_flex_align_items') {
                    $base = 'align-items-';
                } else if ($currentField == 'bootstrap_flex_align_content') {
                    $base = 'align-content-';
                } else {
                    // e.g.:
                    // $currentField == 'bootstrap_flex_direction'
                    // $currentField == 'bootstrap_flex_wrap'
                    $base = 'flex-';
                }

                // Klassen, die vom kleineren Breakpoint geerbt werden, werden nicht erneut ausgeben.
                if (isset($base) && $value != $lastValue) {
                    $arrFlexClasses[$base.$key] = $base . $breakpoint . $value;
                }
                $lastValue = $value;
            }
        }
        return $arrFlexClasses;
    }
}
