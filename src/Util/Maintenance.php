<?php

namespace Kiwi\Contao\BootstrapBundle\Util;

use Contao\Automator;
use Contao\LayoutModel;
use Contao\System;
use Contao\ThemeModel;

class Maintenance
{
    public function updateScssMTimeAndPurgeScriptCache()
    {
        $rootDir = System::getContainer()->getParameter('kernel.project_dir');
        $filesPath = System::getContainer()->getParameter('contao.upload_path');
        $basePath = $rootDir . '/' . $filesPath . '/themes';

        $colorFilePath = $basePath . '/colorvars.scss';
        $colorFileMTime = filemtime($colorFilePath);

        $colThemes = ThemeModel::findAll();
        foreach ($colThemes as $objTheme) {
            $themeFolderPath = $basePath . '/' . $objTheme->alias;

            // handle files in custom sub-folders too?
            // $arrExtraThemeFolderPaths = glob($themeFolderPath . '/*', GLOB_ONLYDIR);
            $arrThemeFilePaths = glob($themeFolderPath . '/*.scss');

            $themeFilesMaxMTime = $colorFileMTime;
            foreach ($arrThemeFilePaths as $themeFilePath) {
                $themeFilesMaxMTime = max($themeFilesMaxMTime, filemtime($themeFilePath));
            }

            $colLayouts = LayoutModel::findByPid($objTheme->id);
            foreach ($colLayouts as $objLayout) {
                $layoutFolderPath = $themeFolderPath . '/' . $objLayout->alias;
                $includeFilePath = $layoutFolderPath . '/layout-' . $objLayout->alias . '.scss';
                $mTime = filemtime($includeFilePath);

                if ($themeFilesMaxMTime > $mTime) {
                    touch($includeFilePath);
                    continue;
                }

                $arrLayoutFilePaths = glob($layoutFolderPath . '/*.scss');
                foreach ($arrLayoutFilePaths as $layoutFilePath) {
                    if (filemtime($layoutFilePath) > $mTime) {
                        touch($includeFilePath);
                        continue 2;
                    }
                }
            }
        }

        $automator = new Automator();
        $automator->purgeScriptCache();
    }
}
