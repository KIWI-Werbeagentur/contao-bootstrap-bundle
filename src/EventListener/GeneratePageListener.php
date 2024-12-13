<?php

namespace Kiwi\Contao\BootstrapBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\LayoutModel;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\System;

/**
 * @Hook("generatePage")
 */
class GeneratePageListener
{
    /**
     * Replace Contao's layout.css and responsive.css with bootstrap-compatible versions.
     * Handles other css framework files as well, to preserve the correct order. See: PageRegular::createHeaderScripts
     * Also includes JS for Bootstrap.
     */
    public function __invoke(PageModel $objPage, LayoutModel $objLayout)
    {
        $arrFramework = StringUtil::deserialize($objLayout->framework, true);

        $objTheme = $objLayout->getRelated('pid');

        foreach ($arrFramework as $strFile) {
            switch ($strFile) {
                case 'layout.css':
                    $GLOBALS['TL_FRAMEWORK_CSS'][] = 'bundles/kiwibootstrap/contao_layout.css';
                    break;
                case 'responsive.css':
                    $GLOBALS['TL_FRAMEWORK_CSS'][] = 'bundles/kiwibootstrap/contao_responsive.css';
                    break;
                case 'bs_styles':
                    $GLOBALS['TL_FRAMEWORK_CSS'][] = "files/themes/{$objTheme->alias }/{$objLayout->alias}/imports.scss";
                    break;
                case 'tinymce.css':
                    $GLOBALS['TL_FRAMEWORK_CSS'][] = 'assets/contao/css/' . basename($strFile, '.css') . '.min.css';
                    break;
                default:
                    break;
            }
        }
        dump($GLOBALS['TL_FRAMEWORK_CSS']);
        dump($GLOBALS['TL_CSS']);

        // set to an empty array, so files do not get added twice
        $objLayout->framework = serialize([]);

        $GLOBALS['TL_JAVASCRIPT'][] = 'responsive/breakpoints.js';

        // Bootstrap js
        $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/kiwibootstrap/twbs-combined/bootstrap.bundle.min.js|static';
        // Kiwi js
        $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/kiwibootstrap/kiwi_bootstrap_resize.js|static';
    }
}
