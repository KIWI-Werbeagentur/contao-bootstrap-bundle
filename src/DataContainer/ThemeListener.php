<?php

namespace Kiwi\Contao\BootstrapBundle\DataContainer;

use Contao\Database;
use Contao\DataContainer;
use Contao\StringUtil;
use Contao\System;
use Symfony\Component\Filesystem\Filesystem;

class ThemeListener
{
    /**
     * @param DataContainer $objDca
     * @throws Exception
     */
    public function generateAlias(DataContainer $objDca)
    {
        $autoAlias = false;

        // Generate alias if there is none
        if ($objDca->activeRecord->alias == '') {
            $autoAlias = true;
            $objDca->activeRecord->alias = StringUtil::generateAlias($objDca->activeRecord->name);
        }

        $objAlias = Database::getInstance()->prepare("SELECT id FROM tl_theme WHERE alias=? AND id!=?")
            ->execute($objDca->activeRecord->alias, $objDca->id);

        // Check whether the event alias exists
        if ($objAlias->numRows) {
            if (!$autoAlias) {
                throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $objDca->activeRecord->alias));
            }

            $objDca->activeRecord->alias .= '-' . $objDca->id;
        }
        Database::getInstance()->prepare("UPDATE tl_theme SET alias=? WHERE id=?")->execute($objDca->activeRecord->alias, $objDca->activeRecord->id);
    }

    public function generateThemeCustomizationFile(DataContainer $objDca)
    {
        $strToRoot = "../../..";
        $fs = new Filesystem();

        $themeAlias = $objDca->activeRecord->alias;
        $targetPath = System::getContainer()->getParameter('kernel.project_dir') . '/files/themes/' . $themeAlias;

        if (!$themeAlias) {
            return;
        }

        if (!$fs->exists($targetPath)) {
            $fs->mkdir($targetPath);
        }

        if (!$fs->exists($targetPath . '/themevars-' . $themeAlias . '.scss')) {
            file_put_contents($targetPath . '/' . 'themevars-' . $themeAlias . '.scss', '// Hier können Bootstrap-Variablen für alle Layouts des Themes überschrieben werden.' . "\n" . '// Eine Datei mit allen möglichen Variablen findet sich unter "vendor/twbs/scss/_variables.scss".');
        }

        if (!$fs->exists($targetPath . '/theme-' . $themeAlias . '.scss')) {
            $fs->touch($targetPath . '/theme-' . $themeAlias . '.scss');
        }

        $objTheme = $objDca->activeRecord;
        $themeAlias = $objTheme->alias;
        $targetPath = System::getContainer()->getParameter('kernel.project_dir') . '/files/themes/' . $themeAlias . '/';

        $arrComponents = [];
        if ($GLOBALS['responsive']['bootstrapComponents']) {
            foreach ($GLOBALS['responsive']['bootstrapComponents'] as $strComponent) {
                if (!$objTheme->responsiveBootstrapComponents || in_array($strComponent, (StringUtil::deserialize($objTheme->responsiveBootstrapComponents) ?? []))) {
                    $strPath = str_replace("__ROOT__", $strToRoot, $GLOBALS['responsive']['bootstrap']);
                    $arrComponents[] = "@import '$strPath/$strComponent';";
                }
            }
        }

        file_put_contents($targetPath . '_imports-' . $themeAlias . '.scss', implode("\n", $arrComponents));
    }
}