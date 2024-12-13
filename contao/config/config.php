<?php

use Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration;

$GLOBALS['FE_MOD']['navigationMenu']['bootstrapNavbar']     = Kiwi\Contao\BootstrapBundle\FrontendModule\BootstrapNavbar::class;

$GLOBALS['responsive']['config'] = BootstrapConfiguration::class;

$GLOBALS['responsive']['bootstrap'] = 'vendor/twbs/bootstrap/scss';
$GLOBALS['responsive']['custom'] = "@import '__ROOT__/vendor/kiwi/contao-bootstrap-bundle/assets/scss/kiwi'";

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