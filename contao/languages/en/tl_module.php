<?php

$GLOBALS['TL_LANG']['tl_module']['alt'] = [
    0 => "Alternate text",
];

$GLOBALS['TL_LANG']['tl_module']['inlineSvg'] = ['Inline SVG', 'Embed the logo inline as an SVG tag.'];

$GLOBALS['TL_LANG']['tl_module']['breakpoint'] = [
    0 => "Breakpoint",
    1 => "Specify from which viewport on the navigation is shown in its desktop view."
];


$GLOBALS['TL_LANG']['tl_module']['animation'] = [
    0 => "Animation"
];

$GLOBALS['TL_LANG']['tl_module']['theme'] = [
    0 => "Theme",
    'options' => [
        'dark' => 'Dark',
        'light' => 'Light'
    ]
];

$GLOBALS['TL_LANG']['tl_module']['module'] = [
    0 => "Navigation module",
    1 => "Which navigation module should be included?"
];

// Matching mod_bootstrap_navbar: "out" is outside the collapsible navigation container, "in" inside it
$GLOBALS['TL_LANG']['tl_module']['position'] = [
    0 => "Position",
    1 => "At which position should the content be inserted?",
    'options' => [
        'out::before' => 'Before the navigation container',
        'in::before' => 'In the navigation container, before the navigation',
        'in::after' => 'In the navigation container, after the navigation',
        'out::after' => 'After the navigation container',
    ]
];
