<?php

namespace Kiwi\Contao\BootstrapBundle\DataContainer;

use Contao\DataContainer;
use Contao\StringUtil;
use Contao\System;
use Kiwi\Contao\BootstrapBundle\Service\SpacingsFileRegenerator;
use Symfony\Component\Filesystem\Filesystem;

class ThemeListener
{
    use AliasGeneratorTrait;

    /**
     * @param DataContainer $objDca
     * @throws \Exception
     */
    public function generateAlias(DataContainer $objDca): void
    {
        $this->generateAliasForTable($objDca, 'tl_theme');
    }

    public function generateThemeCustomizationFile(DataContainer $objDca): void
    {
        $strToRoot = "../../..";
        $fs = new Filesystem();

        $record = $objDca->getCurrentRecord() ?? [];
        $themeAlias = $record['alias'] ?? null;
        $targetPath = System::getContainer()->getParameter('kernel.project_dir') . '/files/themes/';
        $themePath = $targetPath . $themeAlias;

        if (!$themeAlias) {
            return;
        }

        if (!$fs->exists($themePath)) {
            $fs->mkdir($themePath);
        }

        if (!$fs->exists($themePath . '/themevars-' . $themeAlias . '.scss')) {
            file_put_contents($themePath . '/' . 'themevars-' . $themeAlias . '.scss', '// Hier können Bootstrap-Variablen für alle Layouts des Themes überschrieben werden.' . "\n" . '// Eine Datei mit allen möglichen Variablen findet sich unter "vendor/twbs/scss/_variables.scss".');
        }

        if (!$fs->exists($themePath . '/theme-' . $themeAlias . '.scss')) {
            $fs->touch($themePath . '/theme-' . $themeAlias . '.scss');
        }

        // Delegate to the shared regenerator service so this code path and the
        // SpacingsCacheWarmer perform exactly the same render/diff/backup/write.
        System::getContainer()->get(SpacingsFileRegenerator::class)->regenerate();

        $themePath = System::getContainer()->getParameter('kernel.project_dir') . '/files/themes/' . $themeAlias . '/';

        $arrComponents = [];
        if ($GLOBALS['responsive']['bootstrapComponents']) {
            foreach ($GLOBALS['responsive']['bootstrapComponents'] as $strComponent) {
                if (!($record['responsiveBootstrapComponents'] ?? null) || in_array($strComponent, StringUtil::deserialize($record['responsiveBootstrapComponents'], true))) {
                    $strPath = str_replace("__ROOT__", $strToRoot, $GLOBALS['responsive']['bootstrap']);
                    $arrComponents[] = "@import '$strPath/$strComponent';";
                }
            }
        }

        file_put_contents($themePath . '_imports-' . $themeAlias . '.scss', implode("\n", $arrComponents));
    }
}
