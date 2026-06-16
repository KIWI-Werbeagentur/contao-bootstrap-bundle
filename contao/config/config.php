<?php

use Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration;
use Kiwi\Contao\BootstrapBundle\Configuration\Grid\SubsystemRegistry;

$GLOBALS['FE_MOD']['navigationMenu']['bootstrapNavbar']     = Kiwi\Contao\BootstrapBundle\FrontendModule\BootstrapNavbar::class;

$GLOBALS['responsive']['config'] = BootstrapConfiguration::class;

// Wire the horizontal gutter to the value-derived space scale: the gutter applies
// an option as `.gx{infix}-<key>` setting `--bs-gutter-x`, with header/footer/main
// partials. Bundle default config is prepended in KiwiBootstrapBundle::prependExtension().
SubsystemRegistry::register('gutter', [], ['main', 'header', 'footer'], ['prefix' => 'gx', 'property' => '--bs-gutter-x']);

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