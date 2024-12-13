<?php

namespace Kiwi\Contao\BootstrapBundle\Controller;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\StringUtil;
use Contao\System;
use Contao\ThemeModel;
use Kiwi\Contao\ResponsiveBaseBundle\Controller\ResponsiveAssetsController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class BootstrapAssetsController
{
    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly Environment $twig
    ) {
        $this->framework->initialize();
    }

    #[Route('responsive/styles/bootstrap/{filename}')]
    public function getBootstrapFolder($filename)
    {
        $strRoot = System::getContainer()->getParameter('kernel.project_dir');
        $objResponse = new Response((file_get_contents("$strRoot/{$GLOBALS['responsive']['bootstrap']}/$filename")));

        $objResponse->headers->set('Content-Type', 'text/css');

        return $objResponse;
    }

    #[Route('responsive/styles/theme/{themeAlias}.scss')]
    public function getThemeImportFile($themeAlias)
    {
        if (!$themeAlias) {
            return;
        }

        $objTheme = ThemeModel::findBy('alias', $themeAlias);

        $arrComponents = [];
        if ($GLOBALS['responsive']['bootstrapComponents']) {
            foreach ($GLOBALS['responsive']['bootstrapComponents'] as $strComponent) {
                if (!$objTheme->responsiveBootstrapComponents || in_array($strComponent, (StringUtil::deserialize($objTheme->responsiveBootstrapComponents) ?? [])))
                    $arrComponents[] = $this->twig->render('@KiwiBootstrap/scss-import.html.twig', [
                        'strImport' => $strComponent,
                        'strToRoot' => '/responsive/styles/bootstrap'
                    ]);
            }
        }

        $objResponse = new Response(implode("\n",$arrComponents));

        $objResponse->headers->set('Content-Type', 'text/css');

        return $objResponse;
    }

    #[Route('responsive/styles/layout/{themeAlias}/{layoutAlias}.scss')]
    public function getLayoutImportFile($themeAlias, $layoutAlias)
    {
        $strImports = file_get_contents(System::getContainer()->getParameter('kernel.project_dir') . '/vendor/kiwi/contao-bootstrap-bundle/assets/customization/_imports.scss.dist');

        $strImports = str_replace(
            ['__THEMENAME__', '__LAYOUTNAME__','__BOOTSTRAP-STYLES__','__CUSTOM-STYLES__'],
            [$themeAlias, $layoutAlias, $GLOBALS['responsive']['bootstrap'], $GLOBALS['responsive']['custom']],
            $strImports
        );


        $objResponse = new Response($strImports);

        $objResponse->headers->set('Content-Type', 'text/css');

        return $objResponse;
    }
}