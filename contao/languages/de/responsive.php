<?php

$GLOBALS['TL_LANG']['responsive']['flexContent']['between'] = "Verteilt [space-between]";
$GLOBALS['TL_LANG']['responsive']['flexContent']['around'] = "Verteilt mit halben Platz nach außen [space-around]";
$GLOBALS['TL_LANG']['responsive']['flexContent']['evenly'] = "Verteilt mit Platz nach außen [space-evenly]";

// Veraltete benannte Buckets auf der gemeinsamen `spacings`-Referenz — nur sichtbar,
// solange KIWI_BOOTSTRAP_DEPRECATED_SPACINGS sie behält. (Das noop-Label kommt aus
// dem responsive-base-Bundle.)
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

// Pro-DCA-Feld-Optionslabels. Jedes der vier vertical-spacing-Felder ist an einen
// registrierten Partial gebunden (articleTop / articleBottom / groupTop / groupBottom;
// die DCA-Referenz wird pro Feld in contao/dca/responsive.php überschrieben), sodass
// sein `default`-Label nur den eigenen aufgelösten Wert zeigt. Die konfigurierte Skala
// + `default` werden über die Nicht-Konfig-Labels (noop + veraltete Buckets) der
// gemeinsamen `spacings`-Referenz gelegt; der "Default"-Präfix wird auf "Standard"
// lokalisiert.
$spacingFallbackLabels = array_map(
    static fn ($label) => \is_array($label) ? ($label[0] ?? '') : $label,
    $GLOBALS['TL_LANG']['responsive']['spacings'],
);
foreach ([
    'responsiveSpacingTop'         => 'articleTop',
    'responsiveSpacingBottom'      => 'articleBottom',
    'responsiveGroupSpacingTop'    => 'groupTop',
    'responsiveGroupSpacingBottom' => 'groupBottom',
] as $spacingField => $spacingPartial) {
    $spacingOptions = \Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration::subsystemOptionLabels('vertical-spacing', ',', $spacingPartial)
        + $spacingFallbackLabels;
    if (isset($spacingOptions['default'])) {
        $spacingOptions['default'] = preg_replace('/^Default /', 'Standard ', $spacingOptions['default']);
    }
    $GLOBALS['TL_LANG']['responsive'][$spacingField]['options'] = $spacingOptions;
}

$GLOBALS['TL_LANG']['responsive']['breakpoint']['xs'][0] = "Standard (Smartphone)";
$GLOBALS['TL_LANG']['responsive']['breakpoint']['sm'][0] = "Smartphone Landscape";
$GLOBALS['TL_LANG']['responsive']['breakpoint']['md'][0] = "Tablet";
$GLOBALS['TL_LANG']['responsive']['breakpoint']['lg'][0] = "Laptop";
$GLOBALS['TL_LANG']['responsive']['breakpoint']['xl'][0] = "PC";
$GLOBALS['TL_LANG']['responsive']['breakpoint']['xxl'][0] = "TV";

$GLOBALS['TL_LANG']['responsive']['responsiveRowCols'] = [
    0 => 'Elemente pro Zeile <span style="color: #7f7f7f">[row-cols]</span>',
    'auto' => "Automatisch [auto]",
    'options' => [
        'auto' => "Automatisch [auto]",
    ]
];

$GLOBALS['TL_LANG']['responsive']['flexContainer'] = [
    'default' => "Spalten-Element",
    'container-fluid' => "Volle Breite [container-fluid]",
    'container' => "Begrenzte Breite [container]",
    'container-sm' => "Begrenzte Breite ab {$GLOBALS['TL_LANG']['responsive']['breakpoint']['sm'][0]} [container-sm]",
    'container-md' => "Begrenzte Breite ab {$GLOBALS['TL_LANG']['responsive']['breakpoint']['md'][0]} [container-md]",
    'container-lg' => "Begrenzte Breite ab {$GLOBALS['TL_LANG']['responsive']['breakpoint']['lg'][0]} [container-lg]",
    'container-xl' => "Begrenzte Breite ab {$GLOBALS['TL_LANG']['responsive']['breakpoint']['xl'][0]} [container-xl]",
    'container-xxl' => "Begrenzte Breite ab {$GLOBALS['TL_LANG']['responsive']['breakpoint']['xxl'][0]} [container-xxl]",
];

$GLOBALS['TL_LANG']['responsive']['responsiveOrder']['options'] = [
    'default' => "Standard [0]",
    'first' => "Erstes",
    'last' => "Letztes"
];

$GLOBALS['TL_LANG']['responsive']['responsiveGutter'] = [
    0 => 'Horizontaler Rasterabstand <span style="color: #7f7f7f">[gx]</span>',
    1 => 'Bootstrap horizontale Gutter je Viewport. Vertikale Gutter werden separat gesteuert.',
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
    0 => 'Horizontaler Rasterabstand - Inhaltsbereich <span style="color: #7f7f7f">[gx]</span>',
    1 => 'Bootstrap horizontale Gutter für Hauptspalte und Seitenleisten.',
];

$GLOBALS['TL_LANG']['responsive']['responsiveGutterLayoutHeader'] = [
    0 => 'Horizontaler Rasterabstand - Header <span style="color: #7f7f7f">[gx]</span>',
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
    0 => 'Horizontaler Rasterabstand - Footer <span style="color: #7f7f7f">[gx]</span>',
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
    0 => 'Vertikaler Abstand zwischen Reihen <span style="color: #7f7f7f">[gy]</span>',
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
    0 => 'Container-Padding <span style="color: #7f7f7f">[cx]</span>',
    1 => 'Der linke/rechte Abstand nach außen des Containers je Viewport.  Wird nur angewendet, wenn sich der Container über den ganzen Viewport erstreckt.',
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
    0 => 'Header Container-Padding <span style="color: #7f7f7f">[cx]</span>',
    1 => 'Der linke/rechte Abstand nach außen des Header-Abschnitt-Containers je Viewport. Wird nur angewendet, wenn sich der Container über den ganzen Viewport erstreckt.',
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
    0 => 'Footer Container-Padding <span style="color: #7f7f7f">[cx]</span>',
    1 => 'Der linke/rechte Abstand nach außen des Footer-Abschnitt-Containers je Viewport. Wird nur angewendet, wenn sich der Container über den ganzen Viewport erstreckt.',
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
