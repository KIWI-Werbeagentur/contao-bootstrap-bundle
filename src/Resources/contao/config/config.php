<?php

$GLOBALS['BE_MOD']['design']['kiwiColor'] = [
    'tables' => ['tl_kiwi_color'],
];

$GLOBALS['TL_CTE']['miscellaneous']['w-100']     = Kiwi\Contao\BootstrapBundle\Elements\ContentLineBreak::class;

$GLOBALS['FE_MOD']['navigationMenu']['bootstrapNavbar']     = Kiwi\Contao\BootstrapBundle\FrontendModule\BootstrapNavbar::class;

$GLOBALS['TL_HOOKS']['getContentElement'][]		= array(Kiwi\Contao\BootstrapBundle\EventListener\GetContentElementListener::class, 'getBootstrapContentElement');
$GLOBALS['TL_HOOKS']['getFrontendModule'][]		= array(Kiwi\Contao\BootstrapBundle\EventListener\GetFrontendModuleListener::class, 'getBootstrapFrontendModule');
$GLOBALS['TL_HOOKS']['parseTemplate'][]			= array(Kiwi\Contao\BootstrapBundle\EventListener\ParseTemplateListener::class, 'parseBootstrapTemplate');
$GLOBALS['TL_HOOKS']['parseWidget'][]			= array(Kiwi\Contao\BootstrapBundle\EventListener\ParseWidgetListener::class, 'addBootstrapClassesToWidget');
$GLOBALS['TL_HOOKS']['loadDataContainer'][]	    = array(Kiwi\Contao\BootstrapBundle\EventListener\LoadDataContainerListener::class, 'addBootstrapToDca');
$GLOBALS['TL_HOOKS']['kiwi_addWrapperClasses'][]	    = array(Kiwi\Contao\BootstrapBundle\EventListener\KiwiAddWrapperClassesListener::class, 'addWrapperClasses');

$GLOBALS['TL_MODELS']['tl_kiwi_color']   = Kiwi\Contao\BootstrapBundle\Models\KiwiColorModel::class;

$GLOBALS['TL_PURGE']['folders']['scripts'] = [
    'callback' => [\Kiwi\Contao\BootstrapBundle\Util\Maintenance::class, 'updateScssMTimeAndPurgeScriptCache'],
    'affected' => &$GLOBALS['TL_PURGE']['folders']['scripts']['affected'],
];
