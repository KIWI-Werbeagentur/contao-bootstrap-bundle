<?php

$GLOBALS['TL_DCA']['tl_layout']['config']['onsubmit_callback'][]=array(\Kiwi\Contao\BootstrapBundle\DataContainer\LayoutListener::class,'generateAlias');
$GLOBALS['TL_DCA']['tl_layout']['config']['onsubmit_callback'][]=array(\Kiwi\Contao\BootstrapBundle\DataContainer\LayoutListener::class, 'generateLayoutCustomizationFiles');
$GLOBALS['TL_DCA']['tl_layout']['palettes']['default'] = str_replace(';{header_legend}',',alias;{header_legend}', $GLOBALS['TL_DCA']['tl_layout']['palettes']['default']);
$GLOBALS['TL_DCA']['tl_layout']['palettes']['default'] = str_replace('{column_legend}','{column_legend},bootstrap_breakpoints,bootstrap_container_width', $GLOBALS['TL_DCA']['tl_layout']['palettes']['default']);

/* Header und Footer Breite / Container zur Höhenangabe hinzufügen */
$GLOBALS['TL_DCA']['tl_layout']['subpalettes']['rows_2rwh'] .= ',bootstrap_header_width';
$GLOBALS['TL_DCA']['tl_layout']['subpalettes']['rows_2rwf'] .= ',bootstrap_footer_width';
$GLOBALS['TL_DCA']['tl_layout']['subpalettes']['rows_3rw'] = str_replace(
    ['headerHeight', 'footerHeight'],
    ['headerHeight,bootstrap_header_width', 'footerHeight,bootstrap_footer_width'],
    $GLOBALS['TL_DCA']['tl_layout']['subpalettes']['rows_3rw']
);

/* Bootstrap Spaltenbreite ersetzt die Standard-Breitenangabe für Seitenspalten */
$GLOBALS['TL_DCA']['tl_layout']['subpalettes']['cols_2cll'] = 'col_left';
$GLOBALS['TL_DCA']['tl_layout']['subpalettes']['cols_2clr'] = 'col_right';
$GLOBALS['TL_DCA']['tl_layout']['subpalettes']['cols_3cl'] = $GLOBALS['TL_DCA']['tl_layout']['subpalettes']['cols_2cll'] . ',' . $GLOBALS['TL_DCA']['tl_layout']['subpalettes']['cols_2clr'];


$GLOBALS['TL_DCA']['tl_layout']['fields']['alias'] = array
(
    'exclude'                 => true,
    'search'                  => true,
    'inputType'               => 'text',
    'eval'                    => array('readonly'=>true,'rgxp'=>'folderalias', 'doNotCopy'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
    'sql'                     => "varchar(255) BINARY NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['rows']['default'] = '3rw';

$GLOBALS['TL_DCA']['tl_layout']['fields']['bootstrap_header_width'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['bootstrap_header_width'],
    'reference'               => &$GLOBALS['TL_LANG']['tl_layout']['bootstrap_width_reference'],
    'inputType'               => 'select',
    'default'                 => 'container',
    'options'                 => array(
        'container',
        'container-md',
        'container-lg',
        'container-xl',
        'container-xxl',
        'container-fluid',
    ),
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "varchar(24) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_layout']['fields']['bootstrap_footer_width'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['bootstrap_footer_width'],
    'reference'               => &$GLOBALS['TL_LANG']['tl_layout']['bootstrap_width_reference'],
    'inputType'               => 'select',
    'default'                 => 'container',
    'options'                 => array(
        'container',
        'container-md',
        'container-lg',
        'container-xl',
        'container-xxl',
        'container-fluid',
    ),
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "varchar(24) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['bootstrap_container_width'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['bootstrap_container_width'],
    'reference'               => &$GLOBALS['TL_LANG']['tl_layout']['bootstrap_width_reference'],
    'inputType'               => 'select',
    'default'                 => 'container-fluid',
    'options'                 => array(
        'container',
        'container-md',
        'container-lg',
        'container-xl',
        'container-xxl',
        'container-fluid',
    ),
    'eval'                    => array('tl_class'=>'w50', 'submitOnChange' => true),
    'sql'                     => "varchar(24) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['cols']['default'] = '1cl';
if(array_key_exists('tl_class', $GLOBALS['TL_DCA']['tl_layout']['fields']['cols']['eval'])){
    $GLOBALS['TL_DCA']['tl_layout']['fields']['cols']['eval']['tl_class'] .= ' clr';
}else{
    $GLOBALS['TL_DCA']['tl_layout']['fields']['cols']['eval']['tl_class'] = 'clr';
}


foreach (['left', 'right'] as $column) {
    $GLOBALS['TL_DCA']['tl_layout']['fields']['col_'.$column]=array(
        'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['col_'.$column],
        'exclude'                 => true,
        'default'              => [
            "xs"  => "3",
            "sm"  => "inherit",
            "md"  => "inherit",
            "lg"  => "inherit",
            "xl"  => "inherit",
            "xxl" => "inherit",
        ],
        'input_field_callback'    => array(Kiwi\Contao\BootstrapBundle\DataContainer\BootstrapListener::class, 'loadBreakpoints'),
        'sql'                     => 'blob NULL'
    );
}

$GLOBALS['TL_DCA']['tl_layout']['fields']['framework']['options'][] = 'bs_styles';
$GLOBALS['TL_DCA']['tl_layout']['fields']['framework']['sql'] = "varchar(255) NOT NULL default 'a:3:{i:0;s:10:\"layout.css\";i:1;s:14:\"responsive.css\";i:2;s:9:\"bs_styles\";}'";


unset($GLOBALS['TL_DCA']['tl_layout']['fields']['static']);
