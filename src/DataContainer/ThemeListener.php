<?php

namespace Kiwi\Contao\BootstrapBundle\DataContainer;

use Contao\DataContainer;
use Contao\Message;
use Contao\StringUtil;
use Contao\System;
use Kiwi\Contao\BootstrapBundle\Service\SpacingsFileRegenerator;
use Symfony\Component\Filesystem\Exception\IOException;
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
        // The record is already saved at this point: report a failed write instead of
        // aborting the request, so the editor sees it and can fix the permissions.
        try {
            System::getContainer()->get(SpacingsFileRegenerator::class)->regenerate();
        } catch (IOException $e) {
            System::getContainer()->get('monolog.logger.contao.error')->error('Could not regenerate the spacings file: ' . $e->getMessage(), ['exception' => $e]);
            Message::addError($e->getMessage());
        }

        $projectDir = System::getContainer()->getParameter('kernel.project_dir');
        $themePath = $projectDir . '/files/themes/' . $themeAlias . '/';

        // Relative path from the imports file's directory back to the project root, derived
        // like in LayoutImportsFileRegenerator instead of hardcoding the depth.
        $strToRoot = rtrim($fs->makePathRelative($projectDir, $themePath), '/');

        $arrComponents = [];
        foreach ($GLOBALS['responsive']['bootstrapComponents'] ?? [] as $strComponent) {
            if (!($record['responsiveBootstrapComponents'] ?? null) || in_array($strComponent, StringUtil::deserialize($record['responsiveBootstrapComponents'], true))) {
                $strPath = str_replace("__ROOT__", $strToRoot, (string) ($GLOBALS['responsive']['bootstrap'] ?? ''));
                $arrComponents[] = "@import '$strPath/$strComponent';";
            }
        }

        file_put_contents($themePath . '_imports-' . $themeAlias . '.scss', implode("\n", $arrComponents));
    }
}
