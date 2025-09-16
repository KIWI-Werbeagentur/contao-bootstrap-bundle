<?php

namespace Kiwi\Contao\BootstrapBundle\DataContainer;

use Contao\Config;
use Contao\Database;
use Contao\DataContainer;
use Contao\Input;
use Contao\StringUtil;

class BootstrapListener
{
    /**
     * Input field callback for
     * @param DataContainer $dc
     * @return string HTML output for input_field_callback
     */
    public static function loadBreakpoints(DataContainer $dc): string
    {
        $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/kiwibootstrap/preview.js';

        $strTable = $dc->table;
        $currentField = $dc->field;
        $currentID = $dc->activeRecord->id;

        if (Input::post('FORM_SUBMIT')) {
            $arrColumns = [];
            foreach (static::getBreakpoints() as $arrBreakpoint) {
                $value = Input::post($currentField . '_' . $arrBreakpoint['name'] . '_' . $currentID);
                if ($value) {
                    $arrColumns[$arrBreakpoint['name']] = $value;
                }
            }
            if ($arrColumns) {
                $strColumns = serialize($arrColumns);
                Database::getInstance()->prepare("UPDATE " . $strTable . " SET " . $currentField . "=? WHERE id=?")->execute($strColumns, $currentID);
            }
        }

        $columnsQuery = Database::getInstance()->prepare("SELECT " . $currentField . " FROM " . $strTable . " WHERE id=?")->execute($currentID)->{$currentField};

        $arrColumns = [];

        if ($columnsQuery) {
            $breakpoints = StringUtil::deserialize($columnsQuery);
            foreach ($breakpoints as $breakpoint => $columns) {
                $arrColumns[$breakpoint] = $columns;
            }
        }

        if (!$arrColumns) {
            if (isset($GLOBALS['TL_DCA'][$strTable]['fields'][$currentField]['default']) && is_array($GLOBALS['TL_DCA'][$strTable]['fields'][$currentField]['default'])) {
                $arrColumns = $GLOBALS['TL_DCA'][$strTable]['fields'][$currentField]['default'];
            }
        }

        //Felder erstellen
        $strField = "<div style='height:auto;' class='clr widget'>";
        $label = $GLOBALS['TL_DCA'][$strTable]['fields'][$currentField]['label'][0] ?? $GLOBALS['TL_LANG']['bootstrap'][$currentField][0] ?? $currentField;
        $strField .= "<h3><label>" . $label . "</label></h3>";
        $strField .= "<div style='display:flex;margin:0 -5px'>";

        foreach (static::getBreakpoints() as $arrBreakpoint) {
            $arrClass = '';
            if (isset($dc->activeRecord->type) && $dc->activeRecord->type == "w-100") {
                $arrClass = array(
                    'd' . ($arrBreakpoint['name'] == 'xs' ? '' : '-' . $arrBreakpoint['name']) . '-none-only' => 'kein Umbruch',
                    'umbruch' => 'Umbruch'
                );
            } else {
                if (strpos($currentField, 'col') !== false) {
                    $arrClass = array();
                    if ($strTable != "tl_layout") {
                        $arrClass = array(
                            'auto' => 'inhaltsabhängig',
                            // 'stretch' => 'gestreckt',
                        );
                    }
                    $arrClass['none-only'] = 'unsichtbar';
                    if (in_array($currentField, ['col', 'col_right', 'col_left', 'col_list', 'col_item'])) {
                        foreach ($GLOBALS['TL_LANG']['bootstrap']['col_options'] as $k => $v) {
                            $arrClass[$k] = $v;
                        }
                    } elseif ($currentField == 'bootstrap_default_columns') {
                        foreach ($GLOBALS['TL_LANG']['bootstrap']['row_col_options'] as $k => $v) {
                            $arrClass[$k] = $v;
                        }
                    }
                }
            }


            if (!empty($arrClass)) {
                $strField .= "<div class='bs_".($currentField == 'bootstrap_default_columns'?'wrapper_':'')."col_" . $arrBreakpoint['name'] . "' style='flex: 0 0 16.66%;padding: 0 5px;box-sizing: border-box'>";

                $strField .= "<h3><label>" . $GLOBALS['TL_LANG']['bootstrap']['bootstrap_devices'][$arrBreakpoint['name']] . " - " . $arrBreakpoint['name'] . "</label></h3>";
                $strField .= "<select class='tl_select' name='" . $currentField . '_' . $arrBreakpoint['name'] . '_' . $currentID . "' onfocus='Backend.getScrollOffset()' onchange='BootstrapPreview.reload(this, \"".$arrBreakpoint['name']."\")'>";
                if ($arrBreakpoint['name'] != 'xs') {
                    $strField .= "<option value='inherit'>erben</option>";
                }
                foreach ($arrClass as $key => $size) {
                    $selected = "";
                    if (isset($arrColumns[$arrBreakpoint['name']]) && $arrColumns[$arrBreakpoint['name']] == $key) {
                        $selected = "selected";
                    }
                    $strField .= "<option value='" . $key . "' " . ($selected) . ">" . $size . "</option>";
                }
                $strField .= "</select>";
                $strField .= "</div>";
            }
        }

        $strField .= "</div>";
        $helpLabel = $GLOBALS['TL_DCA'][$strTable]['fields'][$currentField]['label'][1] ?? $GLOBALS['TL_LANG']['bootstrap'][$currentField][1] ?? null;
        if ($helpLabel) {
            $strField .= "<p class='tl_help tl_tip'>" . $helpLabel . "</p>";
        }
        $strField .= "</div>";

        return $strField;
    }

    public static function getBreakpoints()
    {
        return array(
            'xs' => [
                'name' => 'xs',
                'size' => '0',
                'container' => '100',
                'unit' => '%',
            ],
            'sm' => [
                'name' => 'sm',
                'size' => '576',
                'container' => '540',
                'unit' => 'px',
            ],
            'md' => [
                'name' => 'md',
                'size' => '768',
                'container' => '720',
                'unit' => 'px',
            ],
            'lg' => [
                'name' => 'lg',
                'size' => '992',
                'container' => '960',
                'unit' => 'px',
            ],
            'xl' => [
                'name' => 'xl',
                'size' => '1200',
                'container' => '1140',
                'unit' => 'px',
            ],
            'xxl' => [
                'name' => 'xxl',
                'size' => '1400',
                'container' => '1320',
                'unit' => 'px',
            ],
        );
    }

    public function getBreakpointNames(DataContainer $dc)
    {
        return array_column(static::getBreakpoints(),'name');
    }

    /**
     * Called from tl_content or tl_article to generate HTML and SVG content reflecting the current column configuration
     * @param DataContainer $dc
     * @param string $label
     * @return string HTML output for input_field_callback
     */
    public static function generatePreview(DataContainer $dc, string $label): string
    {
        return static::getPreviewHtml($dc);
    }

    private static function getPreviewHtml($dc) {
        $strTable = $dc->table;
        // Should be either 'bootstrap_preview' or 'bootstrap_preview_wrapper'
        $currentField = $dc->field;

        $strField = '<div class="clr widget">
        <style>
            .' . $currentField . '_legend {
                padding-top: 13px !important;
                font-weight: 600;
            }
            .' . $currentField . ' {
                display: flex;
                justify-content: space-between;
                margin-top: 8px;
            }
            .' . $currentField . ' .breakpoint {
                flex: 0 0 16.666%;
                text-align: center;
                box-sizing: border-box;
                padding: 0 5px;
            }
            .' . $currentField . ' svg.preview {
                width: 100%;
                height: auto;
            }
            #frame {
                stroke: none;
                fill: #303030;
                fill-rule: evenodd;
                clip-rule: evenodd;
            }
            #screen, .buttons {
                fill: #f7f7f7;
            }
            #container {
                fill: #ff00001A;
            }
            .column {
                fill: #28282820;
            }
            .column_text {
                fill: #666;
            }
        </style>';
        $label =  $GLOBALS['TL_DCA'][$strTable]['fields'][$currentField]['label'][0] ?? $GLOBALS['TL_LANG']['bootstrap'][$currentField][0] ?? $currentField;
        $strField .= '
        <legend class="' . $currentField . '_legend">' . $label . '</legend>
        <div id="ctrl_'. $currentField .'" class="' . $currentField . '">';
        [$strCols, $type] = static::getColumnConfiguration($dc);
        if ($arrCols = StringUtil::deserialize($strCols)) {
            $breakpointSelection = [
                'sm' => 0,
                'md' => 1,
                'lg' => 2,
                'xl' => 3,
                'xxl' => 4
            ];
            if ($dc->table == 'tl_content') {
                $strTable = $dc->activeRecord->wrapper_id ? 'tl_content' : 'tl_article';
                $objWrapper = Database::getInstance()->prepare("SELECT * FROM " . $strTable . " WHERE id=?")->execute($dc->activeRecord->wrapper_id ?: $dc->activeRecord->pid);
                $selectedBreakpoint = $breakpointSelection[$objWrapper->bootstrap_container_breakpoint] ?? null;
                $containerWidth = $objWrapper->bootstrap_container_width;
            } else if ($dc->table == 'tl_article') {
                $selectedBreakpoint = $breakpointSelection[$dc->activeRecord->bootstrap_container_breakpoint] ?? null;
                $containerWidth = $dc->activeRecord->bootstrap_container_width;
            } else {
                $selectedBreakpoint = 'sm';
                $containerWidth = 'fluid';
            }

            $lastColumns = 0;

            foreach (BootstrapListener::getBreakpoints() as $breakpoint) {
                $strField .= '<div class="breakpoint ' . $breakpoint['name'] . '">';
                switch ($breakpoint['name']) {
                    case 'xs':
                        $device = 'smartphone-v';
                        $container = 'fluid';
                        break;
                    case 'sm':
                        $device = 'smartphone-h';
                        $container = ($containerWidth == 'container' && $selectedBreakpoint <= 0) ? 'contained' : 'fluid';
                        break;
                    case 'md':
                        $device = 'tablet';
                        $container = ($containerWidth == 'container' && $selectedBreakpoint <= 1) ? 'contained' : 'fluid';
                        break;
                    case 'lg':
                        $device = 'laptop';
                        $container = ($containerWidth == 'container' && $selectedBreakpoint <= 2) ? 'contained' : 'fluid';
                        break;
                    case 'xl':
                        $device = 'pc';
                        $container = ($containerWidth == 'container' && $selectedBreakpoint <= 3) ? 'contained' : 'fluid';
                        break;
                    case 'xxl':
                        $device = 'tv';
                        $container = ($containerWidth == 'container' && $selectedBreakpoint <= 4) ? 'contained' : 'fluid';
                        break;
                }

                $columns = $arrCols[$breakpoint['name']] ?? '';
                if ($columns == "none-only" && $dc->activeRecord->type == 'w-100') {
                    $columns = 'keinUmbruch';
                } elseif ($columns == 'inherit') {
                    $columns = $lastColumns;
                }

                $lastColumns = $columns;

                $strField .= static::generateSVG($device, $container, $columns, $type == 'row-cols');

                $strField .= '</div>';
            }
            $helpLabel = $GLOBALS['TL_DCA'][$strTable]['fields'][$currentField]['label'][1] ?? $GLOBALS['TL_LANG']['bootstrap'][$currentField][1] ?? null;
            $strField .= '</div>';
            $strField .= '<p class="tl_help tl_tip" style="margin-top:5px" title="">' . $helpLabel . '</p>';
            $strField .= '</div>';
        } else {
            $strField .= '<p><em>'.$GLOBALS['TL_LANG']['bootstrap']['bootstrap_preview_error'].'</em></p></div></div>';
        }

        return $strField;
    }

    /**
     * Generates an SVG for the device with the applicable container and column configuration
     * @param $device string
     * @param $container string
     * @param $columns string
     * @return string SVG code
     */
    private static function generateSVG(string $device, string $container, string $columns, bool $isRowCols = false): string
    {
        $svg = '<svg id="svg-' . $device . '-';
        if ($columns == "none-only") {
            $svg .= $columns;
        }
        else {
            $svg .= $container . '-' . $columns;
        }
        $svg .= '" width="100px" height="100px" class="preview" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve"';
        if ($device == 'smartphone-v') {
            $deviceWidth = 433.6;
            $deviceHeight = 642.7;
            $screenWidth = 360;
            $screenHeight = 508;
            $frameLeft = ($deviceWidth - $screenWidth)/2;
            $frameTop = ($deviceWidth - $screenWidth)/2;
            //$frameLeft = 37.8;
            //$frameTop = 36.6;
            $frame = '<path d="M403.1,642.7H30.6C13.8,642.7,0,628.9,0,612.1V30.6C0,13.8,13.8,0,30.6,0h372.5c16.8,0,30.6,13.8,30.6,30.6
	v581.5C433.6,628.9,419.9,642.7,403.1,642.7z"/>';
            $buttons = '<path d="M237.9,595.1c0.4-11.1-9.1-21.3-20.1-21.5c-10.9-0.2-19.8,8.5-20.2,19.7c-0.4,10.6,8.4,20.2,19.1,20.9
	C226.5,614.9,237.5,605,237.9,595.1z"/>';
        } else if ($device == 'smartphone-h') {
            $deviceWidth = 719;
            $deviceHeight = 485.2;
            $screenWidth = 576;
            $screenHeight = 408;
            $frameLeft = 40.8;
            $frameTop = 38.6;
            $frame = '<path d="M719,34.2V451c0,18.8-15.4,34.2-34.2,34.2H34.2C15.4,485.2,0,469.7,0,451V34.2C0,15.4,15.4,0,34.2,0h650.5
	C703.6,0.1,719,15.4,719,34.2z"/>';
            $buttons = '<path d="M668,220.1c-12.4-0.4-23.8,10.2-24.1,22.5c-0.2,12.2,9.5,22.2,22,22.6c11.9,0.4,22.6-9.4,23.4-21.4
	C690.1,232.8,679,220.5,668,220.1z"/>';
        } else if ($device == 'tablet') {
            $deviceWidth = 882.3;
            $deviceHeight = 1229.1;
            $screenWidth = 768;
            $screenHeight = 1084;
            $frameLeft = ($deviceWidth - $screenWidth)/2 - 0.05;
            $frameTop = ($deviceWidth - $screenWidth)/2 - 0.95;
            $frameLeft = 57.1;
            $frameTop = 56.2;
            $frame = '<path d="M47.5,0h787.3c26.2,0,47.5,21.3,47.5,47.5v1134.1c0,26.2-21.3,47.5-47.5,47.5H47.5c-26.2,0-47.5-21.3-47.5-47.5
	V47.5C0,21.3,21.3,0,47.5,0z"/>';
            $buttons = '<path d="M464.7,1185.6c0.5-12.9-10.6-24.7-23.5-25c-12.8-0.2-23.3,10-23.6,22.8c-0.4,12.3,9.8,23.4,22.3,24.3
	C451.4,1208.5,464.2,1197,464.7,1185.6z"/>';
        } else if ($device == 'laptop') {
            $deviceWidth = 1234.3;
            $deviceHeight = 807.4;
            $screenWidth = 992;
            $screenHeight = 642;
            $frameLeft = ($deviceWidth - $screenWidth)/2 - 0.05;
            $frameTop = ($deviceWidth - $screenWidth)/2 - 75.25;
            $frameLeft = 121.1;
            $frameTop = 45.9;
            $frame = '<path d="M118.7,0h996.9c23.3,0,42.1,18.9,42.1,42.1v649.6c0,23.3-18.9,42.1-42.1,42.1H118.7c-23.3,0-42.1-18.9-42.1-42.1V42.1C76.5,18.9,95.4,0,118.7,0z"/>';
            $frame .= '<path d="M24.6,756.2h1185c13.6,0,24.6,11,24.6,24.6v1.9c0,13.6-11,24.6-24.6,24.6H24.6C11,807.4,0,796.3,0,782.8v-1.9
	C0,767.2,11,756.2,24.6,756.2z"/>';
            $buttons = '<circle cx="617.1" cy="22.1" r="10.1"/>';
            $buttons .= '<circle cx="981.9" cy="781.8" r="10.1"/>';
            $buttons .= '<ellipse transform="matrix(0.1602 -0.9871 0.9871 0.1602 82.6192 1660.6604)" cx="1017.2" cy="781.8" rx="10.1" ry="10.1"/>';
            $buttons .= '<path d="M1147.9,791.9h-95.3c-5.6,0-10.1-4.5-10.1-10.1v0c0-5.6,4.5-10.1,10.1-10.1h95.3c5.6,0,10.1,4.5,10.1,10.1v0
		C1158,787.4,1153.5,791.9,1147.9,791.9z"/>';
        } else if ($device == 'pc') {
            $deviceWidth = 1322.3;
            $deviceHeight = 1127.4;
            $screenWidth = 1200;
            $screenHeight = 776;
            $frameLeft = ($deviceWidth - $screenWidth)/2 - 0.05;
            $frameTop = ($deviceWidth - $screenWidth)/2 - 0.75;
            $frameLeft = 61.1;
            $frameTop = 60.4;
            $frame = '<path d="M1270.8,0H51.5C23.1,0,0,24.6,0,54.9v846.7c0,30.3,23.1,54.9,51.5,54.9h413.9v114.5h-181
	c-8.1,0-14.6,6.5-14.6,14.6v27.1c0,8.1,6.5,14.6,14.6,14.6h753.4c8.1,0,14.6-6.5,14.6-14.6v-27.1c0-8.1-6.5-14.6-14.6-14.6h-181
	V956.4h413.9c28.4,0,51.5-24.6,51.5-54.9V54.9C1322.3,24.6,1299.2,0,1270.8,0z"></path>';
            $buttons = '<circle cx="661.1" cy="897.7" r="26.6"/>';
        } else if ($device == 'tv') {
            $deviceWidth = 1535;
            $deviceHeight = 1110;
            $screenWidth = 1400;
            $screenHeight = 772;
            $frameLeft = ($deviceWidth - $screenWidth)/2;
            $frameTop = ($deviceWidth - $screenWidth)/2 + 4.2;
            $frameLeft = 67.5;
            $frameTop = 71.7;
            $frame = '<path d="M1475.2,0H59.8C26.8,0,0,28.5,0,63.7v849.7c0,35.2,26.8,63.7,59.8,63.7h480.5v67.2H330.2c-9.4,0-17,7.6-17,17
	v31.5c0,9.4,7.6,17,17,17h874.6c9.4,0,17-7.6,17-17v-31.5c0-9.4-7.6-17-17-17H994.7v-67.2h480.5c33,0,59.8-28.5,59.8-63.7V63.7
	C1535,28.5,1508.2,0,1475.2,0z"></path>';
            $buttons = '<circle cx="1446.6" cy="910" r="20.9"/>';
            $buttons .= '<circle cx="1387.6" cy="910" r="20.9"/>';
            $buttons .= '<circle cx="1328.7" cy="910" r="20.9"/>';
            $buttons .= '<path d="M658.2,889.1h218.5c8,0,14.4,6.4,14.4,14.4v13.1c0,8-6.4,14.4-14.4,14.4H658.2c-8,0-14.4-6.4-14.4-14.4v-13.1
	C643.8,895.5,650.3,889.1,658.2,889.1z"/>';
        } else { //legacy
            $deviceWidth = 1920;
            $deviceHeight = 1680;
            $screenWidth = 1720;
            $screenHeight = 1056;
            $frameLeft = ($deviceWidth - $screenWidth)/2;
            $frameTop = ($deviceWidth - $screenWidth)/2 + 15;
            $frame = '<path d="m 0 57.6
                  c 0 -28.8, 28.8 -57.6, 57.6 -57.6
                  H 1862.4
                  c 28.8 0, 57.6 6, 57.6 57.6
                  V 1344
                  c 0 28.8, -28.8 57.6, -57.6 57.6
                  h -633.6
                  c 0 192, 72 201.6, 230.4 216
                  c 16.8 0, 16.8 31.2, 0 31.2
                  h -1012.8
                  c -16.8 0, -16.8 -31.2, 0 -31.2
                  c 158.4 -14.4, 230.4 -24, 230.4 -216
                  H 57.6
                  c -28.8 0, -57.6 -28.8, -57.6 -57.6
                  Z"></path>';
            $buttons = '<circle cx="960" cy="1286.4" r="31.2"/>';
        }
        $svg .= ' viewBox="0, 0, '.$deviceWidth.','.$deviceHeight.'">';
        $svg .= '<g id="frame">';
        $svg .= $frame;
        $svg .= '</g>';

        $viewport = '<g id="viewport" transform="translate(' . $frameLeft . ',' . $frameTop . ')">';
        $viewport .= '<rect id="screen" x="0" y="0" width="' . $screenWidth . '" height="' . $screenHeight . '" />';

        if ($columns !== 'none-only') {
            switch ($container) {
                case 'contained':
                    // not accurate, for visual reasons
                    $containerWidth = ($screenWidth / 4) * 3;
                    $containerPosition = ($screenWidth - $containerWidth) / 2;
                    break;
                case 'fluid':
                    $containerWidth = $screenWidth;
                    $containerPosition = 0;
                    break;
            }
            $viewport .= '<rect id="container" x="' . $containerPosition . '" y="0" width="' . $containerWidth . '" height="' . $screenHeight . '" />';

            // Calculate columns
            $gridGutter = 30;
            if ($columns == 'stretch') {
                $viewport .= '<g transform="translate('.$containerPosition.',0)">
                    <rect id="col-' . $columns . '" class="column" x="' . $gridGutter . '" y="'.$gridGutter.'" width="' . ($containerWidth-$gridGutter*2) . '" height="' . ($screenHeight/3) . '" />
                    <g transform="translate('.$gridGutter.','.$gridGutter.')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="'.($containerWidth-$gridGutter*2).'" height="'.($screenHeight/3).'" viewBox="0 0 200 30">
                            <linearGradient id="a" x1="40" y1="6" x2="160" y2="24">
                                <stop offset="0" stop-color="#777"/>
                                <stop offset="1" stop-color="#666"/>
                            </linearGradient>
                            <path fill="url(#a)" d="M 0 15
                            l 15 -15
                            l 10 0
                            l -11 11
                            l 172 0
                            l -11 -11
                            l 10 0
                            l 15 15
                            l -15 15
                            l -10 0
                            l 11 -11
                            l -172 0
                            l 11 11
                            l -10 0
                            z"/>
                        </svg>
                    </g>
                </g>
                <g transform="translate('.$containerPosition.','.($screenHeight/3+$gridGutter).')">
                    <rect id="col-' . $columns . '" class="column" x="' . $gridGutter . '" y="'.$gridGutter.'" width="' . (($containerWidth-$gridGutter*3)/2) . '" height="' . ($screenHeight/3) . '" />
                    <g transform="translate('.$gridGutter.','.$gridGutter.')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="'.(($containerWidth-$gridGutter*3)/2).'" height="'.($screenHeight/3).'" viewBox="0 0 200 30">
                            <linearGradient id="a" x1="40" y1="6" x2="160" y2="24">
                                <stop offset="0" stop-color="#777"/>
                                <stop offset="1" stop-color="#666"/>
                            </linearGradient>
                            <path fill="url(#a)" d="M 0 15
                            l 15 -15
                            l 10 0
                            l -11 11
                            l 172 0
                            l -11 -11
                            l 10 0
                            l 15 15
                            l -15 15
                            l -10 0
                            l 11 -11
                            l -172 0
                            l 11 11
                            l -10 0
                            z"/>
                        </svg>
                    </g>
                    <rect id="col-' . $columns . '" class="column" x="' . ($gridGutter+(($containerWidth-$gridGutter)/2)) . '" y="'.$gridGutter.'" width="' . (($containerWidth-$gridGutter*3)/2) . '" height="' . ($screenHeight/3) . '" />
                    <g transform="translate('.($gridGutter+(($containerWidth-$gridGutter)/2)).','.$gridGutter.')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="'.(($containerWidth-$gridGutter*3)/2).'" height="'.($screenHeight/3).'" viewBox="0 0 200 30">
                            <linearGradient id="a" x1="40" y1="6" x2="160" y2="24">
                                <stop offset="0" stop-color="#777"/>
                                <stop offset="1" stop-color="#666"/>
                            </linearGradient>
                            <path fill="url(#a)" d="M 0 15
                            l 15 -15
                            l 10 0
                            l -11 11
                            l 172 0
                            l -11 -11
                            l 10 0
                            l 15 15
                            l -15 15
                            l -10 0
                            l 11 -11
                            l -172 0
                            l 11 11
                            l -10 0
                            z"/>
                        </svg>
                    </g>
                </g>';

            }
            else if ($columns == 'auto') {
                $viewport .= '<g transform="translate('.$containerPosition.',0)">
                    <rect id="col-' . $columns . '" class="column" x="' . ($containerWidth/2-((($containerWidth/2)-$gridGutter*2)/2)) . '" y="'.$gridGutter.'" width="' . (($containerWidth/2)-$gridGutter*2) . '" height="' . ($screenHeight/3) . '" />
                    <g transform="translate('.($containerWidth/2-((($containerWidth/2)-$gridGutter*2)/2)).','.$gridGutter.')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="'.(($containerWidth/2)-$gridGutter*2).'" height="'.($screenHeight/3).'" viewBox="0 0 200 30">
                            <linearGradient id="a" x1="40" y1="6" x2="160" y2="24">
                                <stop offset="0" stop-color="#777"/>
                                <stop offset="1" stop-color="#666"/>
                            </linearGradient>
                            <path fill="url(#a)" d="M 0 15
                            l 15 -15
                            l 10 0
                            l -11 11
                            l 172 0
                            l -11 -11
                            l 10 0
                            l 15 15
                            l -15 15
                            l -10 0
                            l 11 -11
                            l -172 0
                            l 11 11
                            l -10 0
                            z"/>
                        </svg>
                    </g>
                </g>';
            }
            else if ((int)$columns) {
                $cols = (int)$columns;
                $strColumns = '';
                if ($isRowCols) {
                    $colWidth = ($containerWidth - $gridGutter) / $cols - $gridGutter;
                    $colCount = $cols;
                } else {
                    $colWidth = ($containerWidth - $gridGutter) / 12 * $cols - $gridGutter;
                    $colCount = 12 / $cols;
                }
                $previousWidths = $containerPosition;
                for ($i = 1; $i <= $colCount; $i++) {
                    $strColumns .= '<rect id="col-' . $i . '" class="column" x="' . ($previousWidths + $gridGutter) . '" y="' . $gridGutter . '" width="' . $colWidth . '" height="' . ($screenHeight / 3) . '" />';
                    $previousWidths += $colWidth + $gridGutter;
                }
                $viewport .= $strColumns;
            }
            else if ($columns == "umbruch") {
                $rowHeight = ($screenHeight - $gridGutter * 4)/3;
                $viewport .= '<g transform="translate('.$containerPosition.',0)">
                    <rect id="col-' . $columns . '" class="column" x="' . $gridGutter . '" y="'.$gridGutter.'" width="' . (($containerWidth-$gridGutter*3)/2) . '" height="' . ($rowHeight) . '" />
                    <g transform="translate('.((($containerWidth-$gridGutter*3)/2)+$gridGutter*2).','.$gridGutter.')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="'.(($containerWidth-$gridGutter*3)/2).'" height="'.($rowHeight*2+$gridGutter).'" viewBox="0 0 204 70">
                            <linearGradient id="a" x1="40.8" y1="14" x2="163.2" y2="56">
                                <stop offset="0" stop-color="#777"/>
                                <stop offset="1" stop-color="#666"/>
                            </linearGradient>
                            <path fill="url(#a)" d="M 0 35
                            l 15 -15
                            l 10 0
                            l -11 11
                            l 172 0
                            c 7 0 10 -5 10 -10
                            c 0 -5 -3 -10 -10 -10
                            l -15 0
                            l 0 -8
                            l 15 0
                            c 11 0 18 9 18 18
                            c 0 9 -7 18 -18 18
                            l -172 0
                            l 11 11
                            l -10 0
                            z"/>
                        </svg>
                    </g>
                    <rect id="col-' . $columns . '" class="column" x="' . $gridGutter . '" y="'.($gridGutter*2 + ($rowHeight)).'" width="' . (($containerWidth-$gridGutter*3)/2) . '" height="' . ($rowHeight) . '" />
                </g>';
            }
            else if ($columns == 'keinUmbruch') {
                $rowHeight = ($screenHeight - $gridGutter * 4)/3;
                $viewport .= '<g transform="translate('.$containerPosition.',0)">
                    <rect id="col-' . $columns . '" class="column" x="' . $gridGutter . '" y="'.$gridGutter.'" width="' . (($containerWidth-$gridGutter*3)/2) . '" height="' . ($rowHeight) . '" />
                    <rect id="col-' . $columns . '" class="column" x="' . ($gridGutter*2 + (($containerWidth-$gridGutter*3)/2)) . '" y="'.$gridGutter.'" width="' . (($containerWidth-$gridGutter*3)/2) . '" height="' . ($rowHeight) . '" />
                    <g transform="translate('.$gridGutter.','.($gridGutter*2 + $rowHeight).')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="'.(($containerWidth-$gridGutter*2)).'" height="'.($rowHeight*2+$gridGutter).'" viewBox="0 0 264 264">
                            <linearGradient id="b" x1="52.8" y1="52.8" x2="211.2" y2="211.2">
                                <stop offset="0" stop-color="#777"/>
                                <stop offset="1" stop-color="#666"/>
                            </linearGradient>
                            <path fill="url(#b)" d="M 30 132
                            l 15 -15
                            l 10 0
                            l -11 11
                            l 172 0
                            c 7 0 10 -5 10 -10
                            c 0 -5 -3 -10 -10 -10
                            l -15 0
                            l 0 -8
                            l 15 0
                            c 11 0 18 9 18 18
                            c 0 9 -7 18 -18 18
                            l -172 0 l 11 11
                            l -10 0
                            z
                            M 0 132
                            c 0 -72 60 -132 132 -132
                            s 132 60 132 132
                            l -20 0
                            c 0 -41 -16 -69 -40 -87
                            l -120 187
                            c 57 35 157 -4 160 -100
                            l 20 0
                            c 0 72 -60 132 -132 132
                            s -132 -60 -132 -132
                            l 20 0
                            c 1 40 18 68 46 88
                            l 121 -186
                            c -64 -38 -167 1 -167 98
                            z"/>
                        </svg>
                    </g>
                </g>';
            }
        }
        else {
            $eye = '<g transform="translate('.(($screenWidth/3)/2).','.(($screenHeight/3)/2).')"><svg xmlns="http://www.w3.org/2000/svg" width="'.(($screenWidth/3)*2).'" height="'.(($screenHeight/3)*2).'" viewBox="0 0 500 500">
    <linearGradient id="a" x1="101.792" y1="101.792" x2="398.209" y2="398.209">
        <stop offset="0" stop-color="#777"/>
        <stop offset="1" stop-color="#666"/>
    </linearGradient>
    <path fill="url(#a)"
          d="M250 90.812C111.93 90.812 0 162.082 0 250s111.93 159.188 250 159.188S500 337.918 500 250 388.07 90.812 250 90.812zm47.572 57.15l-40.872 40.87c-3.957 3.958-10.434 3.958-14.392 0l-40.89-40.89c15.353-2.063 31.41-3.178 47.958-3.178 16.632 0 32.773 1.115 48.196 3.198zM52.882 250c0-45.06 52.882-83.494 127.212-98.497L145.5 186.1c-3.96 3.956-3.944 10.418.032 14.357l42.774 42.38c3.976 3.94 3.99 10.4.033 14.358L145.76 299.77c-3.958 3.96-3.958 10.436 0 14.393l34.333 34.334c-74.328-15.004-127.21-53.44-127.21-98.497zm196.495 105.235c-16.55 0-32.605-1.114-47.96-3.18l40.892-40.89c3.957-3.958 10.434-3.958 14.39 0l40.874 40.872c-15.424 2.084-31.567 3.198-48.197 3.198zm191.65-81.932c-13.83 32.763-56.197 59.726-112.377 73.01-3.25.815-6.533 1.564-9.833 2.28l34.43-34.43c3.957-3.957 3.957-10.434 0-14.392l-42.577-42.574c-3.957-3.96-3.957-10.435 0-14.394l42.576-42.575c3.96-3.96 3.96-10.435 0-14.393l-34.427-34.428c3.3.715 6.583 1.465 9.834 2.28 56.18 13.284 98.547 40.247 112.375 73.013 3.682 7.75 5.55 15.553 5.55 23.3s-1.868 15.552-5.552 23.303z"/>
</svg></g>';
            $viewport .= $eye;
        }

        $viewport .= '</g>';
        $svg .= $viewport;

        $svg .= '<g class="buttons">';
        $svg .= $buttons;
        $svg .= '</g>';

        $svg .= '</svg>';

        return $svg;
    }

    /**
     * Get the applicable columns for the element or article
     * @param DataContainer $dc
     * @param bool $isWrapper only get default column configuration, not from current element (not to be called from articles)
     * @return mixed serialized array of columns or NULL (if $dc not tl_content or tl_article or no configuration available)
     */
    private static function getColumnConfiguration(DataContainer $dc)
    {
        $currentField = $dc->field;

        $columns = '';
        $type = '';
        // Content element
        if ($dc->table == 'tl_content' && 'bootstrap_preview_wrapper' !== $currentField) {
            if ($dc->activeRecord->overwriteDefaultColumns) {
                $columns = $dc->activeRecord->col;
                $type = 'cols';
            }
            else {
                $strTable = $dc->activeRecord->wrapper_id ? 'tl_content' : $dc->activeRecord->ptable;
                $objWrapper = Database::getInstance()->prepare("SELECT * FROM " . $strTable . " WHERE id=?")->execute($dc->activeRecord->wrapper_id ?: $dc->activeRecord->pid);
                if (($strTable == 'tl_content' && $objWrapper->addBootstrapContainer) || $strTable == 'tl_article') {
                    $columns = $objWrapper->bootstrap_default_columns;
                    $type = 'row-cols';
                }
            }
        }
        // Module
        elseif ($dc->table == 'tl_module') {
            if ($dc->activeRecord->addBootstrap) {
                $columns = $dc->activeRecord->col_list;
                $type = 'cols';
            }
        }
        // Article & Wrapper
        else if (($dc->table == 'tl_content'  && ('element_group' === $dc->activeRecord->type || $dc->activeRecord->type == 'wrapperStart') && 'bootstrap_preview_wrapper' == $currentField) || $dc->table == 'tl_article') {
            $columns = $dc->activeRecord->bootstrap_default_columns;
            $type = 'row-cols';
        }
        return [$columns, $type];
    }

    public static function loadFlexOptions($dc)
    {
        $strTable = $dc->table;
        $currentField = $dc->field;
        $currentID = $dc->activeRecord->id ?? $dc->id;

        if (Input::post('FORM_SUBMIT')) {
            $arrColumns = [];
            foreach (static::getBreakpoints() as $arrBreakpoint) {
                $value = Input::post($currentField . '_' . $arrBreakpoint['name'] . '_' . $currentID);
                if ($value) {
                    $arrColumns[$arrBreakpoint['name']] = $value;
                }
            }
            if ($arrColumns) {
                $strColumns = serialize($arrColumns);
                Database::getInstance()->prepare("UPDATE " . $strTable . " SET " . $currentField . "=? WHERE id=?")->execute($strColumns, $currentID);
            }
        }

        $columnsQuery = Database::getInstance()->prepare("SELECT " . $currentField . " FROM " . $strTable . " WHERE id=?")->execute($currentID)->{$currentField};

        $arrColumns = [];

        if ($columnsQuery) {
            $breakpoints = StringUtil::deserialize($columnsQuery);
            foreach ($breakpoints as $breakpoint => $columns) {
                $arrColumns[$breakpoint] = $columns;
            }
        }

        if (!$arrColumns) {
            if (isset($GLOBALS['TL_DCA'][$strTable]['fields'][$currentField]['default']) && is_array($GLOBALS['TL_DCA'][$strTable]['fields'][$currentField]['default'])) {
                $arrColumns = $GLOBALS['TL_DCA'][$strTable]['fields'][$currentField]['default'];
            }
        }

        //Felder erstellen
        $strField = "<div class='clr widget' style='height:auto;'>";
        $label = $GLOBALS['TL_DCA'][$strTable]['fields'][$currentField]['label'][0] ?? $GLOBALS['TL_LANG']['bootstrap'][$currentField][0] ?? $currentField;
        $strField .= "<h3><label>" . $label . "</label></h3>";
        $strField .= "<div style='display:flex;margin:0 -5px'>";

        foreach (static::getBreakpoints() as $arrBreakpoint) {
            $arrClass = '';
            if (strpos($currentField, 'bootstrap_flex') !== false) {
                $arrClass = [];
                foreach ($GLOBALS['TL_LANG']['bootstrap'][str_replace('bootstrap_', '', $currentField) . '_options'] as $k => $v) {
                    $arrClass[$k] = $v;
                }
            }

            if (!empty($arrClass)) {
                $strField .= "<div class='bs_" . str_replace('bootstrap_', '', $currentField) . "_" . $arrBreakpoint['name'] . "' style='flex: 0 0 16.66%;padding: 0 5px;box-sizing: border-box'>";

                $strField .= "<h3><label>" . $GLOBALS['TL_LANG']['bootstrap']['bootstrap_devices'][$arrBreakpoint['name']] . " - " . $arrBreakpoint['name'] . "</label></h3>";
                $strField .= "<select class='tl_select' name='" . $currentField . '_' . $arrBreakpoint['name'] . '_' . $currentID . "' onfocus='Backend.getScrollOffset()' onchange='BootstrapPreview.reload(this, \"" . $arrBreakpoint['name'] . "\")'>";
                if ($arrBreakpoint['name'] != 'xs') {
                    $strField .= "<option value='inherit'>erben</option>";
                }
                foreach ($arrClass as $key => $size) {
                    $selected = '';
                    if (isset($arrColumns[$arrBreakpoint['name']]) && $arrColumns[$arrBreakpoint['name']] == $key) {
                        $selected = 'selected';
                    }
                    $strField .= "<option value='" . $key . "' " . ($selected) . '>' . $size . '</option>';
                }
                $strField .= '</select>';
                $strField .= '</div>';
            }
        }

        $strField .= '</div>';
        $helpLabel = $GLOBALS['TL_DCA'][$strTable]['fields'][$currentField]['label'][1] ?? $GLOBALS['TL_LANG']['bootstrap'][$currentField][1] ?? null;
        if ($helpLabel) {
            $strField .= "<p class='tl_help tl_tip'>" . $helpLabel . '</p>';
        }
        $strField .= '</div>';

        return $strField;
    }

    public function setSingleSrcFlags($varValue, DataContainer $dataContainer)
    {
        if ($dataContainer->activeRecord) {
            if ('element_group' === $dataContainer->activeRecord->type) {
                $GLOBALS['TL_DCA'][$dataContainer->table]['fields'][$dataContainer->field]['eval']['extensions'] = Config::get('validImageTypes');
            }
        }

        return $varValue;
    }
}
