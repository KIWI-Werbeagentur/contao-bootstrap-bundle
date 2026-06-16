<?php

use Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration;
use Kiwi\Contao\BootstrapBundle\Configuration\Grid\SubsystemRegistry;

$GLOBALS['FE_MOD']['navigationMenu']['bootstrapNavbar']     = Kiwi\Contao\BootstrapBundle\FrontendModule\BootstrapNavbar::class;

$GLOBALS['responsive']['config'] = BootstrapConfiguration::class;

// Wire the horizontal gutter to the value-derived space scale: the gutter applies
// an option as `.gx{infix}-<key>` setting `--bs-gutter-x`, with header/footer/main
// partials. Bundle default config is prepended in KiwiBootstrapBundle::prependExtension().
SubsystemRegistry::register('gutter', [], ['main', 'header', 'footer'], ['prefix' => 'gx', 'property' => '--bs-gutter-x']);

// Wire the vertical row-gap: applies an option as `.row-gap{infix}-<key>` setting the
// `row-gap` property. Single content context (no sections) → no partials, generic default.
SubsystemRegistry::register('row-gap', [], [], ['prefix' => 'row-gap', 'property' => 'row-gap']);

// Wire the container's own horizontal padding (.cx-*) to the space scale, with
// main/header/footer partials. Prefix-only apply (no `property`): the cx classes are
// not flat utilities but compound, fluid-gated, media-banded selectors
// (.container-md.cx-lg-space-4) emitted by the bespoke loop in the generated
// _grid.scss (fed from this config). The `cx` prefix still drives the class map.
SubsystemRegistry::register('container-padding-x', [], ['main', 'header', 'footer'], ['prefix' => 'cx']);

$GLOBALS['responsive']['bootstrap'] = '__ROOT__/vendor/twbs/bootstrap/scss';
$GLOBALS['responsive']['custom'] = "@import '__ROOT__/vendor/kiwi/contao-bootstrap/assets/scss/kiwi'";

$GLOBALS['responsive']['bootstrapComponents'] = [
    "root",
    "reboot",
    "type",
    "images",
    "containers",
    "grid",
    "tables",
    "forms",
    "buttons",
    "transitions",
    "dropdown",
    "button-group",
    "nav",
    "navbar",
    "card",
    "accordion",
    "breadcrumb",
    "pagination",
    "badge",
    "alert",
    "progress",
    "list-group",
    "close",
    "toasts",
    "modal",
    "tooltip",
    "popover",
    "carousel",
    "spinners",
    "offcanvas",
    "placeholders",
];