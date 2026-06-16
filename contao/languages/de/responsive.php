<?php

$GLOBALS['TL_LANG']['responsive']['flexContent']['between'] = "Verteilt [space-between]";
$GLOBALS['TL_LANG']['responsive']['flexContent']['around'] = "Verteilt mit halben Platz nach außen [space-around]";
$GLOBALS['TL_LANG']['responsive']['flexContent']['evenly'] = "Verteilt mit Platz nach außen [space-evenly]";

$GLOBALS['TL_LANG']['responsive']['spacings']['0'][0]   = '0rem [spacer-0]';
$GLOBALS['TL_LANG']['responsive']['spacings']['1'][0]   = '0,5rem [spacer-1]';
$GLOBALS['TL_LANG']['responsive']['spacings']['2'][0]   = '0,75rem [spacer-2]';
$GLOBALS['TL_LANG']['responsive']['spacings']['3'][0]   = '1rem [spacer-3]';
$GLOBALS['TL_LANG']['responsive']['spacings']['4'][0]   = '1,5rem [spacer-4]';
$GLOBALS['TL_LANG']['responsive']['spacings']['5'][0]   = '2rem [spacer-5]';
$GLOBALS['TL_LANG']['responsive']['spacings']['6'][0]   = '2,5rem [spacer-6]';
$GLOBALS['TL_LANG']['responsive']['spacings']['7'][0]   = '3rem [spacer-7]';
$GLOBALS['TL_LANG']['responsive']['spacings']['8'][0]   = '4rem [spacer-8]';
$GLOBALS['TL_LANG']['responsive']['spacings']['9'][0]   = '5rem [spacer-9]';
$GLOBALS['TL_LANG']['responsive']['spacings']['10'][0]  = '6rem [spacer-10]';

$GLOBALS['TL_LANG']['responsive']['spacings']['default'][0]  = "Standard [default]";
$GLOBALS['TL_LANG']['responsive']['spacings']['none'][0]     = "Null Abstand [none]";
$GLOBALS['TL_LANG']['responsive']['spacings']['gap'][0]      = "Standard-Rasterabstand [gap]";
$GLOBALS['TL_LANG']['responsive']['spacings']['gap-half'][0] = "Halber Standard-Rasterabstand [gap-half]";
$GLOBALS['TL_LANG']['responsive']['spacings']['xxs'][0]      = "Extra extra klein [xxs]";
$GLOBALS['TL_LANG']['responsive']['spacings']['xs'][0]       = "Extra klein [xs]";
$GLOBALS['TL_LANG']['responsive']['spacings']['sm'][0]       = "Klein [sm]";
$GLOBALS['TL_LANG']['responsive']['spacings']['md'][0]       = "Mittel [md]";
$GLOBALS['TL_LANG']['responsive']['spacings']['lg'][0]       = "Groß [lg]";
$GLOBALS['TL_LANG']['responsive']['spacings']['xl'][0]       = "Extra Groß [xl]";
$GLOBALS['TL_LANG']['responsive']['spacings']['xxl'][0]      = "Extra Extra Groß [xxl]";

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
    // Optionslabels werden aus der kiwi_bootstrap.grid.gutter-Konfiguration abgeleitet.
    'options' => Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration::gutterOptionLabels(','),
];
// Lokalisiertes Label für die dynamische Standard-Option.
if (isset($GLOBALS['TL_LANG']['responsive']['responsiveGutter']['options']['default'])) {
    $GLOBALS['TL_LANG']['responsive']['responsiveGutter']['options']['default'] = 'Standard [default]';
}

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
    // Optionslabels werden aus der kiwi_bootstrap.grid.row-gap-Konfiguration abgeleitet.
    'options' => Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration::subsystemOptionLabels('row-gap', ','),
];
// Lokalisiertes Label für die dynamische Standard-Option.
if (isset($GLOBALS['TL_LANG']['responsive']['responsiveRowGap']['options']['default'])) {
    $GLOBALS['TL_LANG']['responsive']['responsiveRowGap']['options']['default'] = 'Standard [default]';
}

$GLOBALS['TL_LANG']['responsive']['responsiveContainerPaddingX'] = [
    0 => 'Container-Padding',
    1 => 'Der linke/rechte Abstand nach außen des Containers (cx-*) je Viewport.  Wird nur angewendet, wenn sich der Container über den ganzen Viewport erstreckt.',
    // Optionslabels werden aus der kiwi_bootstrap.grid.container-padding-x-Konfiguration abgeleitet.
    'options' => Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration::subsystemOptionLabels('container-padding-x', ','),
];
// Lokalisiertes Label für die dynamische Standard-Option.
if (isset($GLOBALS['TL_LANG']['responsive']['responsiveContainerPaddingX']['options']['default'])) {
    $GLOBALS['TL_LANG']['responsive']['responsiveContainerPaddingX']['options']['default'] = 'Standard [default]';
}

$GLOBALS['TL_LANG']['responsive']['responsiveContainerPaddingXLayoutHeader'] = [
    0 => 'Header Container-Padding',
    1 => 'Der linke/rechte Abstand nach außen des Header-Abschnitt-Containers (cx-*) je Viewport. Wird nur angewendet, wenn sich der Container über den ganzen Viewport erstreckt.',
];

$GLOBALS['TL_LANG']['responsive']['responsiveContainerPaddingXLayoutFooter'] = [
    0 => 'Footer Container-Padding',
    1 => 'Der linke/rechte Abstand nach außen des Footer-Abschnitt-Containers (cx-*) je Viewport. Wird nur angewendet, wenn sich der Container über den ganzen Viewport erstreckt.',
];
