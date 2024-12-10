<?php

use Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration;
use Kiwi\Contao\BootstrapBundle\EventListener\LoadDataContainerListener;

$GLOBALS['responsive']['config'] = BootstrapConfiguration::class;

$GLOBALS['responsive']['bootstrap'] = '__ROOT__/vendor/twbs/bootstrap/scss';
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