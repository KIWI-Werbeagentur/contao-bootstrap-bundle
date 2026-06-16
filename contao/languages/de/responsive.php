<?php

$GLOBALS['TL_LANG']['responsive']['flexContent']['between'] = "Verteilt [space-between]";
$GLOBALS['TL_LANG']['responsive']['flexContent']['around'] = "Verteilt mit halben Platz nach außen [space-around]";
$GLOBALS['TL_LANG']['responsive']['flexContent']['evenly'] = "Verteilt mit Platz nach außen [space-evenly]";

// Vertical-spacing-Optionslabels (die wertbasierte space-N-Skala + die dynamische
// `default`-Option) werden aus kiwi_bootstrap.grid.vertical-spacing abgeleitet.
// Die iconedSelect-Referenz ist nach [key][0] verschlüsselt. Das automatisch
// erzeugte `default`-Label enthält bereits den aufgelösten Wert (z.B. "Default
// - 1,5rem [default]"); nur den vorderen "Default"-Präfix auf "Standard"
// lokalisieren und Wertsuffix sowie Optionsschlüssel in Klammern beibehalten.
foreach (\Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration::subsystemOptionLabels('vertical-spacing', ',') as $spacingKey => $spacingLabel) {
    $GLOBALS['TL_LANG']['responsive']['spacings'][$spacingKey][0] = $spacingKey === 'default'
        ? preg_replace('/^Default /', 'Standard ', $spacingLabel)
        : $spacingLabel;
}

// Veraltete benannte Buckets (nur sichtbar, solange KIWI_BOOTSTRAP_DEPRECATED_SPACINGS sie behält).
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
    // Das `default`-Label ist auf den Main-Partial beschränkt, da Header/Footer
    // eigene, unabhängige DCA-Felder haben.
    'options' => Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration::gutterOptionLabels('main', ','),
];
// Lokalisierter vorderer "Default"-Präfix (automatisch erzeugter Wertsuffix
// und Optionsschlüssel "[default]" werden beibehalten).
if (isset($GLOBALS['TL_LANG']['responsive']['responsiveGutter']['options']['default'])) {
    $GLOBALS['TL_LANG']['responsive']['responsiveGutter']['options']['default'] = preg_replace(
        '/^Default /',
        'Standard ',
        $GLOBALS['TL_LANG']['responsive']['responsiveGutter']['options']['default'],
    );
}

$GLOBALS['TL_LANG']['responsive']['responsiveGutterLayout'] = [
    0 => 'Horizontaler Rasterabstand Inhaltsbereich',
    1 => 'Bootstrap horizontale Gutter für Hauptspalte und Seitenleisten.',
];

$GLOBALS['TL_LANG']['responsive']['responsiveGutterLayoutHeader'] = [
    0 => 'Horizontaler Rasterabstand Header',
    1 => 'Bootstrap horizontale Gutter für die Kopfzeilen.',
    // Pro-Partial-Optionslabels (auf den Header-Partial beschränkt, damit nur der
    // aufgelöste Header-Defaultwert angezeigt wird).
    'options' => Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration::gutterOptionLabels('header', ','),
];
if (isset($GLOBALS['TL_LANG']['responsive']['responsiveGutterLayoutHeader']['options']['default'])) {
    $GLOBALS['TL_LANG']['responsive']['responsiveGutterLayoutHeader']['options']['default'] = preg_replace(
        '/^Default /',
        'Standard ',
        $GLOBALS['TL_LANG']['responsive']['responsiveGutterLayoutHeader']['options']['default'],
    );
}

$GLOBALS['TL_LANG']['responsive']['responsiveGutterLayoutFooter'] = [
    0 => 'Horizontaler Rasterabstand Footer',
    1 => 'Bootstrap horizontale Gutter für die Fußzeile.',
    // Pro-Partial-Optionslabels (auf den Footer-Partial beschränkt).
    'options' => Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration::gutterOptionLabels('footer', ','),
];
if (isset($GLOBALS['TL_LANG']['responsive']['responsiveGutterLayoutFooter']['options']['default'])) {
    $GLOBALS['TL_LANG']['responsive']['responsiveGutterLayoutFooter']['options']['default'] = preg_replace(
        '/^Default /',
        'Standard ',
        $GLOBALS['TL_LANG']['responsive']['responsiveGutterLayoutFooter']['options']['default'],
    );
}

$GLOBALS['TL_LANG']['responsive']['responsiveRowGap'] = [
    0 => 'Vertikaler Abstand zwischen Reihen',
    1 => 'Bootstrap row-gap je Viewport. Wirkt zwischen umgebrochenen Reihen innerhalb des Containers.',
    // Optionslabels werden aus der kiwi_bootstrap.grid.row-gap-Konfiguration abgeleitet.
    'options' => Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration::subsystemOptionLabels('row-gap', ','),
];
// Lokalisierter vorderer "Default"-Präfix (automatisch erzeugter Wertsuffix
// und Optionsschlüssel "[default]" werden beibehalten).
if (isset($GLOBALS['TL_LANG']['responsive']['responsiveRowGap']['options']['default'])) {
    $GLOBALS['TL_LANG']['responsive']['responsiveRowGap']['options']['default'] = preg_replace(
        '/^Default /',
        'Standard ',
        $GLOBALS['TL_LANG']['responsive']['responsiveRowGap']['options']['default'],
    );
}

$GLOBALS['TL_LANG']['responsive']['responsiveContainerPaddingX'] = [
    0 => 'Container-Padding',
    1 => 'Der linke/rechte Abstand nach außen des Containers (cx-*) je Viewport.  Wird nur angewendet, wenn sich der Container über den ganzen Viewport erstreckt.',
    // Optionslabels werden aus der kiwi_bootstrap.grid.container-padding-x-Konfiguration
    // abgeleitet. Das `default`-Label ist auf den Main-Partial beschränkt, da
    // Header/Footer eigene, unabhängige DCA-Felder haben.
    'options' => Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration::subsystemOptionLabels('container-padding-x', ',', 'main'),
];
// Lokalisierter vorderer "Default"-Präfix (automatisch erzeugter Wertsuffix
// und Optionsschlüssel "[default]" werden beibehalten).
if (isset($GLOBALS['TL_LANG']['responsive']['responsiveContainerPaddingX']['options']['default'])) {
    $GLOBALS['TL_LANG']['responsive']['responsiveContainerPaddingX']['options']['default'] = preg_replace(
        '/^Default /',
        'Standard ',
        $GLOBALS['TL_LANG']['responsive']['responsiveContainerPaddingX']['options']['default'],
    );
}

$GLOBALS['TL_LANG']['responsive']['responsiveContainerPaddingXLayoutHeader'] = [
    0 => 'Header Container-Padding',
    1 => 'Der linke/rechte Abstand nach außen des Header-Abschnitt-Containers (cx-*) je Viewport. Wird nur angewendet, wenn sich der Container über den ganzen Viewport erstreckt.',
    // Pro-Partial-Optionslabels (auf den Header-Partial beschränkt).
    'options' => Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration::subsystemOptionLabels('container-padding-x', ',', 'header'),
];
if (isset($GLOBALS['TL_LANG']['responsive']['responsiveContainerPaddingXLayoutHeader']['options']['default'])) {
    $GLOBALS['TL_LANG']['responsive']['responsiveContainerPaddingXLayoutHeader']['options']['default'] = preg_replace(
        '/^Default /',
        'Standard ',
        $GLOBALS['TL_LANG']['responsive']['responsiveContainerPaddingXLayoutHeader']['options']['default'],
    );
}

$GLOBALS['TL_LANG']['responsive']['responsiveContainerPaddingXLayoutFooter'] = [
    0 => 'Footer Container-Padding',
    1 => 'Der linke/rechte Abstand nach außen des Footer-Abschnitt-Containers (cx-*) je Viewport. Wird nur angewendet, wenn sich der Container über den ganzen Viewport erstreckt.',
    // Pro-Partial-Optionslabels (auf den Footer-Partial beschränkt).
    'options' => Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration::subsystemOptionLabels('container-padding-x', ',', 'footer'),
];
if (isset($GLOBALS['TL_LANG']['responsive']['responsiveContainerPaddingXLayoutFooter']['options']['default'])) {
    $GLOBALS['TL_LANG']['responsive']['responsiveContainerPaddingXLayoutFooter']['options']['default'] = preg_replace(
        '/^Default /',
        'Standard ',
        $GLOBALS['TL_LANG']['responsive']['responsiveContainerPaddingXLayoutFooter']['options']['default'],
    );
}
