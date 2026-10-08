<?php

namespace Kiwi\Contao\BootstrapBundle\DataContainer;

use Contao\DataContainer;
use Contao\Message;
use Contao\System;
use Contao\ThemeModel;
use Kiwi\Contao\BootstrapBundle\Service\LayoutImportsFileRegenerator;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class LayoutListener
{
    use AliasGeneratorTrait;

    public function generateLayoutCustomizationFiles(DataContainer $objDca): void
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

        // The record is already saved at this point: report a failed write instead of
        // aborting the request, so the editor sees it and can fix the permissions.
        try {
            System::getContainer()->get(LayoutImportsFileRegenerator::class)->regenerate($themeAlias, $layoutAlias);
        } catch (IOException $e) {
            System::getContainer()->get('monolog.logger.contao.error')->error('Could not regenerate the layout imports file: ' . $e->getMessage(), ['exception' => $e]);
            Message::addError($e->getMessage());
        }

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
    public function generateAlias(DataContainer $objDca): void
    {
        $this->generateAliasForTable($objDca, 'tl_layout');
    }
}
