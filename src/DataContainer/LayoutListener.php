<?php

namespace Kiwi\Contao\BootstrapBundle\DataContainer;

use Contao\Database;
use Contao\DataContainer;
use Contao\StringUtil;
use Contao\System;
use Contao\ThemeModel;
use Symfony\Component\Filesystem\Filesystem;

class LayoutListener
{

    public function generateLayoutCustomizationFiles(DataContainer $objDca)
    {
        $strToRoot = "../../../..";
        $objTheme = ThemeModel::findByPk($objDca->activeRecord->pid);
        $fs = new Filesystem();

        $layoutAlias = $objDca->activeRecord->alias;
        $themeAlias = $objTheme->alias;
        $targetPath = System::getContainer()->getParameter('kernel.project_dir') . '/files/themes/' . $themeAlias . '/' . $layoutAlias . '/';

        if (!$layoutAlias) {
            return;
        }

        if (!$fs->exists($targetPath)) {
            $fs->mkdir($targetPath);
        }

        if (!$fs->exists($targetPath . '_imports-' . $layoutAlias . '.scss')) {
            $arrData = [
                'themeName' => $themeAlias,
                'layoutName' => $layoutAlias,
                'bootstrapComponents' => "@import '../_imports-{$themeAlias}.scss';",
                'bootstrapStyles' => str_replace('__ROOT__',$strToRoot, $GLOBALS['responsive']['bootstrap']),
                'customStyles' => str_replace('__ROOT__',$strToRoot, $GLOBALS['responsive']['custom'])
            ];
            $strBuffer = System::getContainer()->get('twig')->render('@Contao/responsive/bootstrap_imports.scss.twig', $arrData);

            if (isset($GLOBALS['TL_HOOKS']['alterBootstrapImports']) && \is_array($GLOBALS['TL_HOOKS']['alterBootstrapImports']))
            {
                foreach ($GLOBALS['TL_HOOKS']['alterBootstrapImports'] as $callback)
                {
                    $strBuffer = System::importStatic($callback[0])->{$callback[1]}($arrData, $strBuffer, $this);
                }
            }

            file_put_contents($targetPath . '_imports-' . $layoutAlias . '.scss', $strBuffer);
        }

        if (!$fs->exists($targetPath . 'layoutvars-' . $layoutAlias . '.scss')) {
            file_put_contents($targetPath . 'layoutvars-' . $layoutAlias . '.scss', '// Hier können Bootstrap-Variablen für das Layout überschrieben werden.' . "\n" . '// Eine Datei mit allen möglichen Variablen findet sich unter "vendor/twbs/scss/_variables.scss".' . "\n\n");
        }

        if (!$fs->exists($targetPath . 'layout-' . $layoutAlias . '.scss')) {
            file_put_contents($targetPath . 'layout-' . $layoutAlias . '.scss', '@import "_imports-' . $layoutAlias . '";' . "\n\n");
        }

        return;
    }

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

        $objAlias = Database::getInstance()->prepare("SELECT id FROM tl_layout WHERE alias=? AND id!=?")
            ->execute($objDca->activeRecord->alias, $objDca->id);

        // Check whether the event alias exists
        if ($objAlias->numRows) {
            if (!$autoAlias) {
                throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $objDca->activeRecord->alias));
            }

            $objDca->activeRecord->alias .= '-' . $objDca->id;
        }
        Database::getInstance()->prepare("UPDATE tl_layout SET alias=? WHERE id=?")->execute($objDca->activeRecord->alias, $objDca->activeRecord->id);
    }
}