<?php

$GLOBALS['TL_LANG']['responsive']['flexContent']['between'] = "Distributed [space-between]";
$GLOBALS['TL_LANG']['responsive']['flexContent']['around'] = "Distributed with half space outside [space-around]";
$GLOBALS['TL_LANG']['responsive']['flexContent']['evenly'] = "Distributed with space outside [space-evenly]";

// Deprecated named buckets on the shared `spacings` reference — shown only while
// KIWI_BOOTSTRAP_DEPRECATED_SPACINGS keeps them. (The noop sentinel label comes from
// the responsive-base bundle.)
$GLOBALS['TL_LANG']['responsive']['spacings']['none'][0]     = "Zero spacing [none]";
$GLOBALS['TL_LANG']['responsive']['spacings']['gap'][0]      = "Default horizontal gutter [gap]";
$GLOBALS['TL_LANG']['responsive']['spacings']['gap-half'][0] = "Half default horizontal gutter [gap-half]";
$GLOBALS['TL_LANG']['responsive']['spacings']['xxs'][0]      = "Extra extra small [xxs]";
$GLOBALS['TL_LANG']['responsive']['spacings']['xs'][0]       = "Extra small [xs]";
$GLOBALS['TL_LANG']['responsive']['spacings']['sm'][0]       = "Small [sm]";
$GLOBALS['TL_LANG']['responsive']['spacings']['md'][0]       = "Medium [md]";
$GLOBALS['TL_LANG']['responsive']['spacings']['lg'][0]       = "Large [lg]";
$GLOBALS['TL_LANG']['responsive']['spacings']['xl'][0]       = "Extra large [xl]";
$GLOBALS['TL_LANG']['responsive']['spacings']['xxl'][0]      = "Extra extra large [xxl]";

// Per-DCA-field option labels. Each of the four vertical-spacing fields maps to one
// registered partial (articleTop / articleBottom / groupTop / groupBottom) — the DCA
// reference is overridden per field in contao/dca/responsive.php — so its `default`
// label shows only its own resolved value (like the gutter/cx header/footer fields).
// The config-driven scale + `default` (from kiwi_bootstrap.grid.vertical-spacing) are
// merged over the non-config labels (noop + deprecated buckets) flattened from the
// shared `spacings` reference, so every dropdown entry stays labelled in every mode.
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
    $GLOBALS['TL_LANG']['responsive'][$spacingField]['options'] =
        \Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration::subsystemOptionLabels('vertical-spacing', '.', $spacingPartial)
        + $spacingFallbackLabels;
}

$GLOBALS['TL_LANG']['responsive']['breakpoint']['xs'][0] = "Default (Smartphone)";
$GLOBALS['TL_LANG']['responsive']['breakpoint']['sm'][0] = "Smartphone Landscape";
$GLOBALS['TL_LANG']['responsive']['breakpoint']['md'][0] = "Tablet";
$GLOBALS['TL_LANG']['responsive']['breakpoint']['lg'][0] = "Laptop";
$GLOBALS['TL_LANG']['responsive']['breakpoint']['xl'][0] = "PC";
$GLOBALS['TL_LANG']['responsive']['breakpoint']['xxl'][0] = "TV";

$GLOBALS['TL_LANG']['responsive']['responsiveRowCols'] = [
    0 => 'Items per row <span style="color: #7f7f7f">[row-cols]</span>',
    'auto' => "Automatic [auto]",
    'options' => [
        'auto' => "Automatic [auto]",
    ]
];

$GLOBALS['TL_LANG']['responsive']['flexContainer'] = [
    'default' => "Column-Element",
    'container-fluid' => "Full Width [container-fluid]",
    'container' => "Limited Width [container]",
    'container-sm' => "Limited Width at {$GLOBALS['TL_LANG']['responsive']['breakpoint']['sm'][0]} [container-sm]",
    'container-md' => "Limited Width at {$GLOBALS['TL_LANG']['responsive']['breakpoint']['md'][0]} [container-md]",
    'container-lg' => "Limited Width at {$GLOBALS['TL_LANG']['responsive']['breakpoint']['lg'][0]} [container-lg]",
    'container-xl' => "Limited Width at {$GLOBALS['TL_LANG']['responsive']['breakpoint']['xl'][0]} [container-xl]",
    'container-xxl' => "Limited Width at {$GLOBALS['TL_LANG']['responsive']['breakpoint']['xxl'][0]} [container-xxl]",
];

$GLOBALS['TL_LANG']['responsive']['responsiveOrder']['options'] = [
    'default' => 'Default [0]',
    'first' => "First",
    'last' => "Last"
];


$GLOBALS['TL_LANG']['responsive']['responsiveGutter'] = [
    0 => 'Horizontal grid gutter <span style="color: #7f7f7f">[gx]</span>',
    1 => 'Bootstrap horizontal gutters per viewport. Vertical gutters are configured separately.',
    // Option labels are derived from the kiwi_bootstrap.grid.gutter config (steps,
    // the dynamic `default`, and one per configured variable). The `default` label
    // is scoped to the main partial, since header/footer have their own independent
    // DCA fields.
    'options' => Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration::gutterOptionLabels('main', '.'),
];

$GLOBALS['TL_LANG']['responsive']['responsiveGutterLayout'] = [
    0 => 'Horizontal grid gutter - main <span style="color: #7f7f7f">[gx]</span>',
    1 => 'Bootstrap horizontal gutters for main column and sidebars.',
];

$GLOBALS['TL_LANG']['responsive']['responsiveGutterLayoutHeader'] = [
    0 => 'Horizontal grid gutter - header <span style="color: #7f7f7f">[gx]</span>',
    1 => 'Bootstrap horizontal gutters for the header rows.',
    // Per-partial option labels (scoped to the header partial so editors see only
    // the header's resolved default, not the footer/main values).
    'options' => Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration::gutterOptionLabels('header', '.'),
];

$GLOBALS['TL_LANG']['responsive']['responsiveGutterLayoutFooter'] = [
    0 => 'Horizontal grid gutter - footer <span style="color: #7f7f7f">[gx]</span>',
    1 => 'Bootstrap horizontal gutters for the footer row.',
    // Per-partial option labels (scoped to the footer partial so editors see only
    // the footer's resolved default, not the header/main values).
    'options' => Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration::gutterOptionLabels('footer', '.'),
];

$GLOBALS['TL_LANG']['responsive']['responsiveRowGap'] = [
    0 => 'Vertical gap between rows <span style="color: #7f7f7f">[gy]</span>',
    1 => 'Bootstrap row-gap per viewport. Applied between wrapped rows inside the container.',
    // Option labels are derived from the kiwi_bootstrap.grid.row-gap config.
    'options' => Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration::subsystemOptionLabels('row-gap', '.'),
];

$GLOBALS['TL_LANG']['responsive']['responsiveContainerPaddingX'] = [
    0 => 'Container padding <span style="color: #7f7f7f">[cx]</span>',
    1 => "The container's own left/right padding per viewport. Only applies when the container spans the whole viewport.",
    // Option labels are derived from the kiwi_bootstrap.grid.container-padding-x
    // config. The `default` label is scoped to the main partial, since header/footer
    // have their own independent DCA fields.
    'options' => Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration::subsystemOptionLabels('container-padding-x', '.', 'main'),
];

$GLOBALS['TL_LANG']['responsive']['responsiveContainerPaddingXLayoutHeader'] = [
    0 => 'Header container padding <span style="color: #7f7f7f">[cx]</span>',
    1 => "The header section container's own left/right padding per viewport. Only applies when the container spans the whole viewport.",
    // Per-partial option labels (scoped to the header partial).
    'options' => Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration::subsystemOptionLabels('container-padding-x', '.', 'header'),
];

$GLOBALS['TL_LANG']['responsive']['responsiveContainerPaddingXLayoutFooter'] = [
    0 => 'Footer container padding <span style="color: #7f7f7f">[cx]</span>',
    1 => "The footer section container's own left/right padding per viewport. Only applies when the container spans the whole viewport.",
    // Per-partial option labels (scoped to the footer partial).
    'options' => Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration::subsystemOptionLabels('container-padding-x', '.', 'footer'),
];
