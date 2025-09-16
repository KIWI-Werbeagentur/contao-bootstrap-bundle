<?php

namespace Kiwi\Contao\BootstrapBundle\DataContainer;

use Contao\System;
use Contao\Database;
use Contao\DataContainer;
use Contao\StringUtil;
use Contao\ThemeModel;
use Exception;
use Symfony\Component\Filesystem\Filesystem;

class LayoutListener
{
    public function generateLayoutCustomizationFiles(DataContainer $dc)
    {
        $objTheme = ThemeModel::findByPk($dc->activeRecord->pid);
        $fs = new Filesystem();

        $layoutAlias = $dc->activeRecord->alias;
        $themeAlias = $objTheme->alias;
        $targetPath = System::getContainer()->getParameter('kernel.project_dir') . '/files/themes/' . $themeAlias . '/' . $layoutAlias . '/';

        if (!$layoutAlias) {
            return;
        }

        if (!$fs->exists($targetPath)) {
            $fs->mkdir($targetPath);
        }

        if (!$fs->exists($targetPath . '_imports-' . $layoutAlias . '.scss')) {
            $strImports = file_get_contents(System::getContainer()->getParameter('kernel.project_dir') . '/vendor/kiwi/contao-bootstrap-bundle/src/Resources/customization/_imports.scss.dist');
            $strImports = str_replace(['__THEMENAME__', '__LAYOUTNAME__'], [$themeAlias, $layoutAlias], $strImports);
            file_put_contents($targetPath . '_imports-' . $layoutAlias . '.scss', $strImports);
        }

        if (!$fs->exists($targetPath . 'layoutvars-' . $layoutAlias . '.scss')) {
            file_put_contents($targetPath . 'layoutvars-' . $layoutAlias . '.scss', '// Hier können Bootstrap-Variablen für das Layout überschrieben werden.' . "\n" . '// Eine Datei mit allen möglichen Variablen findet sich unter "vendor/twbs/scss/_variables.scss".' . "\n\n");
        }

        if (!$fs->exists($targetPath . 'layout-' . $layoutAlias . '.scss')) {
            file_put_contents($targetPath . 'layout-' . $layoutAlias . '.scss', '@import "_imports-' . $layoutAlias . '";' . "\n\n");
        }

        if (!$fs->exists($targetPath . '../../colorvars.scss')) {
            (new KiwiColorListener())->updateScssFile();
        }
    }

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

        $objAlias = Database::getInstance()->prepare("SELECT id FROM tl_layout WHERE alias=? AND id!=?")
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
        Database::getInstance()->prepare("UPDATE tl_layout SET alias=? WHERE id=?")->execute($dc->activeRecord->alias,$dc->activeRecord->id);
    }
}
