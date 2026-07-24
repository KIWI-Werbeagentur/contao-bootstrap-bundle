<?php

namespace Kiwi\Contao\BootstrapBundle\DataContainer;

use Contao\Database;
use Contao\DataContainer;
use Contao\StringUtil;
use Contao\System;
use Kiwi\Contao\BootstrapBundle\Service\SpacingsFileRegenerator;
use Symfony\Component\Filesystem\Filesystem;

class ThemeListener
{
    /**
     * @param DataContainer $objDca
     * @throws \Exception
     */
    public function generateAlias(DataContainer $objDca)
    {
        $record = $objDca->getCurrentRecord() ?? [];
        $alias = (string) ($record['alias'] ?? '');
        $autoAlias = false;

        // Generate alias if there is none
        if ($alias === '') {
            $autoAlias = true;
            $alias = StringUtil::generateAlias((string) ($record['name'] ?? ''));
        }

        $objAlias = Database::getInstance()->prepare("SELECT id FROM tl_theme WHERE alias=? AND id!=?")
            ->execute($alias, $objDca->id);

        // Check whether the event alias exists
        if ($objAlias->numRows) {
            if (!$autoAlias) {
                throw new \Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $alias));
            }

            $alias .= '-' . $objDca->id;
        }
        Database::getInstance()->prepare("UPDATE tl_theme SET alias=? WHERE id=?")->execute($alias, $objDca->id);

        // The explicit UPDATE bypasses getCurrentRecord()'s static cache; clear it so the
        // follow-up onsubmit callback (generateThemeCustomizationFile) reads the new alias.
        DataContainer::clearCurrentRecordCache((int) $objDca->id, 'tl_theme');
    }

    public function generateThemeCustomizationFile(DataContainer $objDca)
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
