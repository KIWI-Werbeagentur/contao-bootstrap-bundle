<?php

namespace Kiwi\Contao\BootstrapBundle\Service;

use Contao\StringUtil;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Writes the per-theme `_imports-<theme>.scss`: one @import per Bootstrap component
 * selected in the theme (all of them while none is selected), and only when the
 * content differs from the current file.
 */
class ThemeImportsFileRegenerator
{
    private const TARGET_DIR_PREFIX = '/files/themes/';

    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly string $projectDir,
        private readonly ?ThemeStylesInvalidator $invalidator = null,
    ) {}

    /**
     * @param string|null $bootstrapComponents the theme's serialized responsiveBootstrapComponents
     *
     * @return bool true if the file was (re)written, false on no-op.
     *
     * @throws \Symfony\Component\Filesystem\Exception\IOException if the file cannot be written
     */
    public function regenerate(string $themeAlias, ?string $bootstrapComponents): bool
    {
        if ($themeAlias === '') {
            return false;
        }

        $targetDir  = $this->projectDir . self::TARGET_DIR_PREFIX . $themeAlias . '/';
        $targetFile = $targetDir . '_imports-' . $themeAlias . '.scss';

        if (!$this->filesystem->exists($targetDir)) {
            $this->filesystem->mkdir($targetDir);
        }

        // Relative path from the imports file's directory back to the project root, derived
        // like in LayoutImportsFileRegenerator instead of hardcoding the depth.
        $strToRoot = rtrim($this->filesystem->makePathRelative($this->projectDir, $targetDir), '/');
        $strPath   = str_replace('__ROOT__', $strToRoot, (string) ($GLOBALS['responsive']['bootstrap'] ?? ''));

        $arrComponents = [];
        foreach ($GLOBALS['responsive']['bootstrapComponents'] ?? [] as $strComponent) {
            if (!$bootstrapComponents || \in_array($strComponent, StringUtil::deserialize($bootstrapComponents, true))) {
                $arrComponents[] = "@import '$strPath/$strComponent';";
            }
        }

        $rendered = implode("\n", $arrComponents);
        $current  = $this->filesystem->exists($targetFile) ? file_get_contents($targetFile) : null;

        if ($current === $rendered) {
            return false;
        }

        // dumpFile() writes atomically (temp file + rename) and throws on failure, so a failed
        // write neither reports success nor leaves a truncated file behind.
        $this->filesystem->dumpFile($targetFile, $rendered);

        $this->invalidator?->invalidate($themeAlias);

        return true;
    }
}
