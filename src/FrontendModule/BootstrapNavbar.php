<?php

namespace Kiwi\Contao\BootstrapBundle\FrontendModule;

use Contao\BackendTemplate;
use Contao\CoreBundle\File\Metadata;
use Contao\FilesModel;
use Contao\Module;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\System;
use Contao\Template;
use enshrined\svgSanitize\Sanitizer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @property string $animation may be '' or '0'
 * @property Template $Template
 * @property string $inlineSvg used as bool ('1' / '')
 * @property int $module
 */
class BootstrapNavbar extends Module
{
    protected $strTemplate = 'mod_bootstrap_navbar';

    public function generate(): string
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

    protected function compile(): void
    {
        /** @var PageModel $objPage */
        global $objPage;

        if ($this->animation) {
            $GLOBALS['TL_FRAMEWORK_CSS'][] = 'bundles/kiwibootstrap/burger.css';
        }

        $objFile = FilesModel::findByPk($this->singleSRC);

        if ($objFile !== null) {
            $strAbsolutePath = System::getContainer()->getParameter('kernel.project_dir') . '/' . $objFile->path;
            $this->Template->singleSRC = $objFile->path;

            // Add a cache-busting parameter derived from the file's mtime, like Contao does for
            // layout scripts and stylesheets, so a replaced logo is not served from the cache.
            if (is_file($strAbsolutePath)) {
                $this->Template->singleSRC .= '?v=' . substr(md5((string) filemtime($strAbsolutePath)), 0, 8);
            }

            if ($this->inlineSvg && strtolower((string)$objFile->extension) === 'svg') {
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

            // Resize the logo through the image studio when a size is set, so it is served as a
            // cacheable asset with width/height. Without a size the original path is output as before.
            if (!$this->Template->inlineSingleSRC && array_filter(StringUtil::deserialize($this->imgSize, true))) {
                try {
                    $this->Template->logoPicture = System::getContainer()->get('contao.image.studio')
                        ->createFigureBuilder()
                        ->fromFilesModel($objFile)
                        ->setSize($this->imgSize)
                        ->setMetadata(new Metadata([Metadata::VALUE_ALT => (string) $this->alt]))
                        ->build()
                        ->getLegacyTemplateData()['picture'];
                } catch (\Exception $e) {
                    System::getContainer()->get('monolog.logger.contao')->error($e->getMessage());
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
