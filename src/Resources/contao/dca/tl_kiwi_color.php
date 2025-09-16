<?php

use Contao\DC_Table;

$GLOBALS['TL_DCA']['tl_kiwi_color'] = [
    'config' => [
        'dataContainer' => DC_Table::class,
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
        'onsubmit_callback' => [
            [\Kiwi\Contao\BootstrapBundle\DataContainer\KiwiColorListener::class, 'updateScssFile'],
        ],
        'ondelete_callback' => [
            [\Kiwi\Contao\BootstrapBundle\DataContainer\KiwiColorListener::class, 'updateScssFile'],
        ],
    ],

    'list' => [
        'sorting' => [
            'mode' => 0,
            'flag' => 1,
            'panelLayout' => 'sort,limit',
            'fields' => ['title'],
        ],
        'label' => [
            'fields' => ['value', 'title', 'variable'],
            'format' => '%s',
            'showColumns' => true,
            'label_callback' => [\Kiwi\Contao\BootstrapBundle\DataContainer\KiwiColorListener::class, 'labelCallback'],
        ],
        'operations' => [
            'edit' => [
                'href' => 'act=edit',
                'icon' => 'edit.svg',
            ],
            'copy' => [
                'href' => 'act=copy',
                'icon' => 'copy.svg',
            ],
            'delete' => [
                'href' => 'act=delete',
                'icon' => 'delete.svg',
                'attributes' => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteColorConfirm'] ?? null) . '\'))return false;Backend.getScrollOffset()"',
            ],
        ],
    ],

    'palettes' => [
        'default' => 'title;variable,value',
    ],

    'fields' => [
        'id' => [
            'sql' => "int(10) unsigned NOT NULL auto_increment",
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'title' => [
            'sorting' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'tl_class' => 'w50', 'maxlength' => 255],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'variable' => [
            'sorting' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'rgxp' => 'custom', 'customRgxp' => '#^[a-z]+$#', 'tl_class' => 'w50', 'maxlength' => 32],
            'sql' => "varchar(32) NOT NULL default ''",
        ],
        'value' => [
            'sorting' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'tl_class' => 'w50', 'maxlength' => 255, 'colorpicker' => true, 'decodeEntities' => true],
            'save_callback' => [
                [\Kiwi\Contao\BootstrapBundle\DataContainer\KiwiColorListener::class, 'valueSaveCallback']
            ],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
    ],
];
