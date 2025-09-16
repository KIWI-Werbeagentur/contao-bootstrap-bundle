<?php

namespace Kiwi\Contao\BootstrapBundle\DataContainer;

use Contao\System;
use Contao\Database;
use Contao\DataContainer;
use Contao\StringUtil;
use Exception;
use Symfony\Component\Filesystem\Filesystem;

class ThemeListener
{
    /**
     * @param DataContainer $dc
     * @throws Exception
     */
    public function generateAlias(DataContainer $dc)
    {
        $autoAlias = false;

        // Generate alias if there is none
        if ($dc->activeRecord->alias == '')
        {
            $autoAlias = true;
            $dc->activeRecord->alias = StringUtil::generateAlias($dc->activeRecord->name);
        }

        $objAlias = Database::getInstance()->prepare("SELECT id FROM tl_theme WHERE alias=? AND id!=?")
            ->execute($dc->activeRecord->alias, $dc->id);

        // Check whether the event alias exists
        if ($objAlias->numRows)
        {
            if (!$autoAlias)
            {
                throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $dc->activeRecord->alias));
            }

            $dc->activeRecord->alias .= '-' . $dc->id;
        }
        Database::getInstance()->prepare("UPDATE tl_theme SET alias=? WHERE id=?")->execute($dc->activeRecord->alias,$dc->activeRecord->id);
    }

    public function generateThemeCustomizationFile(DataContainer $dc)
    {
        $fs = new Filesystem();

        $themeAlias = $dc->activeRecord->alias;
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
    }
}
