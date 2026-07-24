<?php

namespace Kiwi\Contao\BootstrapBundle\DataContainer;

use Contao\Database;
use Contao\DataContainer;
use Contao\StringUtil;
use Contao\System;
use Contao\ThemeModel;
use Kiwi\Contao\BootstrapBundle\Service\LayoutImportsFileRegenerator;
use Symfony\Component\Filesystem\Filesystem;

class LayoutListener
{

    public function generateLayoutCustomizationFiles(DataContainer $objDca)
    {
        $record = $objDca->getCurrentRecord() ?? [];
        $objTheme = ThemeModel::findByPk($record['pid'] ?? null);
        $fs = new Filesystem();

        $layoutAlias = $record['alias'] ?? null;
        $themeAlias = $objTheme->alias;
        $targetPath = System::getContainer()->getParameter('kernel.project_dir') . '/files/themes/' . $themeAlias . '/' . $layoutAlias . '/';

        if (!$layoutAlias) {
            return;
        }

        if (!$fs->exists($targetPath)) {
            $fs->mkdir($targetPath);
        }

        System::getContainer()->get(LayoutImportsFileRegenerator::class)->regenerate($themeAlias, $layoutAlias);

        // layoutvars and layout are user-owned scaffold files: created once,
        // never overwritten, so editor customizations survive any rebuild.
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

        $objAlias = Database::getInstance()->prepare("SELECT id FROM tl_layout WHERE alias=? AND id!=?")
            ->execute($alias, $objDca->id);

        // Check whether the event alias exists
        if ($objAlias->numRows) {
            if (!$autoAlias) {
                throw new \Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $alias));
            }

            $alias .= '-' . $objDca->id;
        }
        Database::getInstance()->prepare("UPDATE tl_layout SET alias=? WHERE id=?")->execute($alias, $objDca->id);

        // The explicit UPDATE bypasses getCurrentRecord()'s static cache; clear it so the
        // follow-up onsubmit callback (generateLayoutCustomizationFiles) reads the new alias.
        DataContainer::clearCurrentRecordCache((int) $objDca->id, 'tl_layout');
    }
}
