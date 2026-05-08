<?php

$GLOBALS['TL_LANG']['responsive']['flexContent']['between'] = "Distributed [space-between]";
$GLOBALS['TL_LANG']['responsive']['flexContent']['around'] = "Distributed with half space outside [space-around]";
$GLOBALS['TL_LANG']['responsive']['flexContent']['evenly'] = "Distributed with space outside [space-evenly]";

$GLOBALS['TL_LANG']['responsive']['spacings']['default'][0] = "Default [default]";
$GLOBALS['TL_LANG']['responsive']['spacings']['none'][0] = "No spacing [none]";
$GLOBALS['TL_LANG']['responsive']['spacings']['gap'][0] = "Default horizontal gutter [gap]";
$GLOBALS['TL_LANG']['responsive']['spacings']['gap-half'][0] = "Half default horizontal gutter [gap-half]";
$GLOBALS['TL_LANG']['responsive']['spacings']['xxs'][0] = "Extra extra small [xxs]";
$GLOBALS['TL_LANG']['responsive']['spacings']['xs'][0] = "Extra small [xs]";
$GLOBALS['TL_LANG']['responsive']['spacings']['sm'][0] = "Small [sm]";
$GLOBALS['TL_LANG']['responsive']['spacings']['md'][0] = "Medium [md]";
$GLOBALS['TL_LANG']['responsive']['spacings']['lg'][0] = "Large [lg]";
$GLOBALS['TL_LANG']['responsive']['spacings']['xl'][0] = "Extra large [xl]";
$GLOBALS['TL_LANG']['responsive']['spacings']['xxl'][0] = "Extra extra large [xxl]";

$GLOBALS['TL_LANG']['responsive']['breakpoint']['xs'][0] = "Default (Smartphone)";
$GLOBALS['TL_LANG']['responsive']['breakpoint']['sm'][0] = "Smartphone Landscape";
$GLOBALS['TL_LANG']['responsive']['breakpoint']['md'][0] = "Tablet";
$GLOBALS['TL_LANG']['responsive']['breakpoint']['lg'][0] = "Laptop";
$GLOBALS['TL_LANG']['responsive']['breakpoint']['xl'][0] = "PC";
$GLOBALS['TL_LANG']['responsive']['breakpoint']['xxl'][0] = "TV";

$GLOBALS['TL_LANG']['responsive']['responsiveRowCols'] = [
    0 => "Items per row",
    'auto' => "Automatic [auto]",
    'options' => [
        'auto' => "Automatic [auto]",
    ]
];

$GLOBALS['TL_LANG']['responsive']['flexContainer'] = [
    'default' => "Column-Element",
    'container-fluid' => "Full Width <span class='label-info'>[container-fluid]</span>",
    'container' => "Limited Width <span class='label-info'>[container]</span>",
    'container-sm' => "Limited Width at {$GLOBALS['TL_LANG']['responsive']['breakpoint']['sm'][0]} <span class='label-info'>[container-sm]</span>",
    'container-md' => "Limited Width at {$GLOBALS['TL_LANG']['responsive']['breakpoint']['md'][0]} <span class='label-info'>[container-md]</span>",
    'container-lg' => "Limited Width at {$GLOBALS['TL_LANG']['responsive']['breakpoint']['lg'][0]} <span class='label-info'>[container-lg]</span>",
    'container-xl' => "Limited Width at {$GLOBALS['TL_LANG']['responsive']['breakpoint']['xl'][0]} <span class='label-info'>[container-xl]</span>",
    'container-xxl' => "Limited Width at {$GLOBALS['TL_LANG']['responsive']['breakpoint']['xxl'][0]} <span class='label-info'>[container-xxl]</span>",
];

$GLOBALS['TL_LANG']['responsive']['responsiveOrder']['options'] = [
    'first' => "First",
    'last' => "Last"
];


$GLOBALS['TL_LANG']['responsive']['responsiveGutter'] = [
    0 => 'Horizontal grid gutter',
    1 => 'Bootstrap horizontal gutters (gx-*) per viewport. Vertical gutters are configured separately.',
    'options' => [
        'default' => 'Layout preset ($grid-gutter-width)',
        '0' => '0rem [gx-0]',
        '1' => '0.25rem [gx-1]',
        '2' => '0.5rem [gx-2]',
        '3' => '1rem [gx-3]',
        '4' => '1.5rem (default) [gx-4]',
        '5' => '2rem [gx-5]',
        '6' => '2.5rem [gx-6]',
        '7' => '3rem [gx-7]',
        '8' => '4rem [gx-8]',
        '9' => '5rem [gx-9]',
        '10' => '6rem [gx-10]',
    ],
];

$GLOBALS['TL_LANG']['responsive']['responsiveGutterLayout'] = [
    0 => 'Main horizontal grid gutter',
    1 => 'Bootstrap horizontal gutters for main column and sidebars.',
];

$GLOBALS['TL_LANG']['responsive']['responsiveGutterLayoutHeader'] = [
    0 => 'Header horizontal grid gutter',
    1 => 'Bootstrap horizontal gutters for the header rows.',
];

$GLOBALS['TL_LANG']['responsive']['responsiveGutterLayoutFooter'] = [
    0 => 'Footer horizontal grid gutter',
    1 => 'Bootstrap horizontal gutters for the footer row.',
];

$GLOBALS['TL_LANG']['responsive']['responsiveRowGap'] = [
    0 => 'Vertical gap between rows',
    1 => 'Bootstrap row-gap per viewport. Applied between wrapped rows inside the container.',
    'options' => [
        '0'   => '0rem [row-gap-0]',
        '1'   => '0.25rem [row-gap-1]',
        '2'   => '0.5rem [row-gap-2]',
        '3'   => '1rem [row-gap-3]',
        '4'   => '1.5rem [row-gap-4]',
        '5'   => '2rem [row-gap-5]',
        '6'   => '2.5rem [row-gap-6]',
        '7'   => '3rem [row-gap-7]',
        '8'   => '4rem [row-gap-8]',
        '9'   => '5rem [row-gap-9]',
        '10'  => '6rem [row-gap-10]',
    ],
];
