<?php

$GLOBALS['TL_LANG']['responsive']['flexContent']['between'] = "Verteilt [space-between]";
$GLOBALS['TL_LANG']['responsive']['flexContent']['around'] = "Verteilt mit halben Platz nach außen [space-around]";
$GLOBALS['TL_LANG']['responsive']['flexContent']['evenly'] = "Verteilt mit Platz nach außen [space-evenly]";

$GLOBALS['TL_LANG']['responsive']['spacings']['default'][0] = "Standard [default]";
$GLOBALS['TL_LANG']['responsive']['spacings']['none'][0] = "Kein Abstand [none]";
$GLOBALS['TL_LANG']['responsive']['spacings']['gap'][0] = "Standard-Rasterabstand [gap]";
$GLOBALS['TL_LANG']['responsive']['spacings']['gap-half'][0] = "Halber Standard-Rasterabstand [gap-half]";
$GLOBALS['TL_LANG']['responsive']['spacings']['xxs'][0] = "Extra extra klein [xxs]";
$GLOBALS['TL_LANG']['responsive']['spacings']['xs'][0] = "Extra klein [xs]";
$GLOBALS['TL_LANG']['responsive']['spacings']['sm'][0] = "Klein [sm]";
$GLOBALS['TL_LANG']['responsive']['spacings']['md'][0] = "Mittel [md]";
$GLOBALS['TL_LANG']['responsive']['spacings']['lg'][0] = "Groß [lg]";
$GLOBALS['TL_LANG']['responsive']['spacings']['xl'][0] = "Extra Groß [xl]";
$GLOBALS['TL_LANG']['responsive']['spacings']['xxl'][0] = "Extra Extra Groß [xxl]";

$GLOBALS['TL_LANG']['responsive']['breakpoint']['xs'][0] = "Standard (Smartphone)";
$GLOBALS['TL_LANG']['responsive']['breakpoint']['sm'][0] = "Smartphone Landscape";
$GLOBALS['TL_LANG']['responsive']['breakpoint']['md'][0] = "Tablet";
$GLOBALS['TL_LANG']['responsive']['breakpoint']['lg'][0] = "Laptop";
$GLOBALS['TL_LANG']['responsive']['breakpoint']['xl'][0] = "PC";
$GLOBALS['TL_LANG']['responsive']['breakpoint']['xxl'][0] = "TV";

$GLOBALS['TL_LANG']['responsive']['responsiveRowCols'] = [
    0 => "Elemente pro Zeile",
    'auto' => "Automatisch [auto]",
    'options' => [
        'auto' => "Automatisch [auto]",
    ]
];

$GLOBALS['TL_LANG']['responsive']['flexContainer'] = [
    'default' => "Spalten-Element",
    'container-fluid' => "Volle Breite <span class='label-info'>[container-fluid]</span>",
    'container' => "Begrenzte Breite <span class='label-info'>[container]</span>",
    'container-sm' => "Begrenzte Breite ab {$GLOBALS['TL_LANG']['responsive']['breakpoint']['sm'][0]} <span class='label-info'>[container-sm]</span>",
    'container-md' => "Begrenzte Breite ab {$GLOBALS['TL_LANG']['responsive']['breakpoint']['md'][0]} <span class='label-info'>[container-md]</span>",
    'container-lg' => "Begrenzte Breite ab {$GLOBALS['TL_LANG']['responsive']['breakpoint']['lg'][0]} <span class='label-info'>[container-lg]</span>",
    'container-xl' => "Begrenzte Breite ab {$GLOBALS['TL_LANG']['responsive']['breakpoint']['xl'][0]} <span class='label-info'>[container-xl]</span>",
    'container-xxl' => "Begrenzte Breite ab {$GLOBALS['TL_LANG']['responsive']['breakpoint']['xxl'][0]} <span class='label-info'>[container-xxl]</span>",
];

$GLOBALS['TL_LANG']['responsive']['responsiveOrder']['options'] = [
    'first' => "Erstes",
    'last' => "Letztes"
];

$GLOBALS['TL_LANG']['responsive']['responsiveGutter'] = [
    0 => 'Horizontaler Rasterabstand',
    1 => 'Bootstrap horizontale Gutter (gx-*) je Viewport. Vertikale Gutter werden separat gesteuert.',
    'options' => [
        'default' => 'Layout-Vorgabe ($grid-gutter-width)',
        '0' => '0rem [gx-0]',
        '1' => '0,25rem [gx-1]',
        '2' => '0,5rem [gx-2]',
        '3' => '1rem [gx-3]',
        '4' => '1,5rem (Standard) [gx-4]',
        '5' => '2rem [gx-5]',
        '6' => '2,5rem [gx-6]',
        '7' => '3rem [gx-7]',
        '8' => '4rem [gx-8]',
        '9' => '5rem [gx-9]',
        '10' => '6rem [gx-10]',
    ],
];

$GLOBALS['TL_LANG']['responsive']['responsiveGutterLayout'] = [
    0 => 'Horizontaler Rasterabstand Inhaltsbereich',
    1 => 'Bootstrap horizontale Gutter für Hauptspalte und Seitenleisten.',
];

$GLOBALS['TL_LANG']['responsive']['responsiveGutterLayoutHeader'] = [
    0 => 'Horizontaler Rasterabstand Header',
    1 => 'Bootstrap horizontale Gutter für die Kopfzeilen.',
];

$GLOBALS['TL_LANG']['responsive']['responsiveGutterLayoutFooter'] = [
    0 => 'Horizontaler Rasterabstand Footer',
    1 => 'Bootstrap horizontale Gutter für die Fußzeile.',
];

$GLOBALS['TL_LANG']['responsive']['responsiveRowGap'] = [
    0 => 'Vertikaler Abstand zwischen Reihen',
    1 => 'Bootstrap row-gap je Viewport. Wirkt zwischen umgebrochenen Reihen innerhalb des Containers.',
    'options' => [
        '0'   => '0rem [row-gap-0]',
        '1'   => '0,25rem [row-gap-1]',
        '2'   => '0,5rem [row-gap-2]',
        '3'   => '1rem [row-gap-3]',
        '4'   => '1,5rem [row-gap-4]',
        '5'   => '2rem [row-gap-5]',
        '6'   => '2,5rem [row-gap-6]',
        '7'   => '3rem [row-gap-7]',
        '8'   => '4rem [row-gap-8]',
        '9'   => '5rem [row-gap-9]',
        '10'  => '6rem [row-gap-10]',
    ],
];
