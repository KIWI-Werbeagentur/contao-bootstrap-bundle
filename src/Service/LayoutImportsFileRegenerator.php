<?php

namespace Kiwi\Contao\BootstrapBundle\Service;

use Contao\System;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

/**
 * Render the per-layout `_imports-<layout>.scss` from the Twig template and
 * write it to disk only when the rendered output differs from the current
 * file content.
 *
 * The file is intentionally NOT treated as user-owned: bundle template updates
 * (e.g. new `@import` lines for newly shipped customization SCSS) need to flow
 * through automatically, otherwise rendered CSS silently desyncs from the
 * bundle expectations. Projects that need to customize the imports list must
 * either override `@Contao/responsive/bootstrap_imports.scss.twig` in their
 * `templates/responsive/` directory or use the `alterBootstrapImports` hook.
 */
class LayoutImportsFileRegenerator
{
    private const TWIG_TEMPLATE     = '@Contao/responsive/bootstrap_imports.scss.twig';
    private const TARGET_DIR_PREFIX = '/files/themes/';

    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly Environment $twig,
        private readonly string $projectDir,
    ) {}

    /**
     * @return bool true if the file was (re)written, false on no-op.
     */
    public function regenerate(string $themeAlias, string $layoutAlias): bool
    {
        if ($themeAlias === '' || $layoutAlias === '') {
            return false;
        }

        $targetDir  = $this->projectDir . self::TARGET_DIR_PREFIX . $themeAlias . '/' . $layoutAlias . '/';
        $targetFile = $targetDir . '_imports-' . $layoutAlias . '.scss';

        if (!$this->filesystem->exists($targetDir)) {
            $this->filesystem->mkdir($targetDir);
        }

        // Relative path from the rendered file's directory back to the project
        // root. Derived from the actual paths so the bundle does not need to
        // hardcode the depth of `files/themes/<theme>/<layout>/`.
        $strToRoot = rtrim($this->filesystem->makePathRelative($this->projectDir, $targetDir), '/');

        $arrData = [
            'themeName'           => $themeAlias,
            'layoutName'          => $layoutAlias,
            'bootstrapComponents' => "@import '../_imports-{$themeAlias}.scss';",
            'bootstrapStyles'     => str_replace('__ROOT__', $strToRoot, (string) ($GLOBALS['responsive']['bootstrap'] ?? '')),
            'customStyles'        => str_replace('__ROOT__', $strToRoot, (string) ($GLOBALS['responsive']['custom'] ?? '')),
        ];

        $rendered = $this->twig->render(self::TWIG_TEMPLATE, $arrData);

        // Let project hooks alter the final buffer. Matches the legacy flow in
        // LayoutListener so e.g. contao-designer's AlterBootstrapImports hook
        // (which replaces the buffer with its own template render) keeps
        // working without changes.
        if (isset($GLOBALS['TL_HOOKS']['alterBootstrapImports']) && \is_array($GLOBALS['TL_HOOKS']['alterBootstrapImports'])) {
            foreach ($GLOBALS['TL_HOOKS']['alterBootstrapImports'] as $callback) {
                $rendered = System::importStatic($callback[0])->{$callback[1]}($arrData, $rendered, $this);
            }
        }

        $current = $this->filesystem->exists($targetFile) ? file_get_contents($targetFile) : null;

        if ($current === $rendered) {
            return false;
        }

        file_put_contents($targetFile, $rendered);

        return true;
    }
}
