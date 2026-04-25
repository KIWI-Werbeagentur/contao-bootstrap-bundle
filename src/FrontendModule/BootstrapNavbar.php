<?php

namespace Kiwi\Contao\BootstrapBundle\FrontendModule;

use Contao\BackendTemplate;
use Contao\FilesModel;
use Contao\Module;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\System;
use enshrined\svgSanitize\Sanitizer;
use Symfony\Component\HttpFoundation\Request;

class BootstrapNavbar extends Module
{
    protected $strTemplate = 'mod_bootstrap_navbar';

    public function generate()
    {
        if (System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest(System::getContainer()->get('request_stack')->getCurrentRequest() ?? Request::create(''))) {
            $objTemplate = new BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### ' . strtoupper($GLOBALS['TL_LANG']['FMD']['bootstrapNavbar'][0]) . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        return parent::generate();
    }

    protected function compile()
    {
        /** @var PageModel $objPage */
        global $objPage;

        if ($this->animation) {
            $GLOBALS['TL_FRAMEWORK_CSS'][] = 'bundles/kiwibootstrap/burger.css';
        }

        $objFile = FilesModel::findByPk($this->singleSRC);

        if ($objFile !== null) {
            $this->Template->singleSRC = $objFile->path;

            if ($this->inlineSvg && strtolower((string)$objFile->extension) === 'svg') {
                $strAbsolutePath = System::getContainer()->getParameter('kernel.project_dir') . '/' . $objFile->path;

                if (is_file($strAbsolutePath) && is_readable($strAbsolutePath)) {
                    $strSvg = file_get_contents($strAbsolutePath);

                    if ($strSvg !== false) {
                        $sanitizer = new Sanitizer();
                        $sanitizer->removeXMLTag(true);
                        $strSvg = $sanitizer->sanitize($strSvg);

                        if ($strSvg !== false) {
                            // Strip any remaining DOCTYPE / leading whitespace before <svg
                            $strSvg = preg_replace('/^.*?(?=<svg\b)/is', '', $strSvg);
                            $this->Template->inlineSingleSRC = $strSvg;
                        }
                    }
                }
            }
        }

        $strLocalePrefix = '';
        if ($objPage->urlPrefix !== "") {
            $strLocalePrefix = '/' . $objPage->urlPrefix . '/';
        }
        $this->Template->localePrefix = $strLocalePrefix;

        /** @var ModuleModel $objNavigation */
        $objNavigation = ModuleModel::findByPk($this->module);
        /** @var Module $objNavigationModule */
        $objNavigationModule = new $GLOBALS['FE_MOD']['navigationMenu'][$objNavigation->type]($objNavigation);
        $this->Template->navigationHTML = $objNavigationModule->generate();
    }
}
