<?php

namespace Kiwi\Contao\BootstrapBundle\Service;

use Composer\InstalledVersions;
use Contao\System;
use Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration;
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
        private readonly ?ThemeStylesInvalidator $invalidator = null,
    ) {}

    /**
     * @return bool true if the file was (re)written, false on no-op.
     *
     * @throws \Symfony\Component\Filesystem\Exception\IOException if the file cannot be written
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
            'legacyGrid'          => BootstrapConfiguration::usesLegacyGrid(),
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

        // Appended here rather than in the template, so overrides and hooks cannot drop it:
        // a bundle update then always rewrites the file, which recompiles the layout's CSS.
        $rendered .= $this->versionStamp();

        $current = $this->filesystem->exists($targetFile) ? file_get_contents($targetFile) : null;

        if ($current === $rendered) {
            return false;
        }

        // dumpFile() writes atomically (temp file + rename) and throws on failure, so a failed
        // write neither reports success nor leaves a truncated file behind.
        $this->filesystem->dumpFile($targetFile, $rendered);

        $this->invalidator?->invalidate($themeAlias, $layoutAlias);

        return true;
    }

    /**
     * One comment line per package whose SCSS the layout compiles: this bundle and the
     * package that holds the bootstrapStyles path (none when the path is in no package).
     */
    private function versionStamp(): string
    {
        $packages = ['kiwi/contao-bootstrap'];

        $bootstrapPath = realpath(str_replace('__ROOT__', $this->projectDir, (string) ($GLOBALS['responsive']['bootstrap'] ?? '')));
        if ($bootstrapPath !== false) {
            $owner = $this->findOwningPackage($bootstrapPath);
            if ($owner !== null && $owner !== 'kiwi/contao-bootstrap') {
                $packages[] = $owner;
            }
        }

        $stamp = '';
        foreach ($packages as $package) {
            $stamp .= "\n// {$package}: " . InstalledVersions::getPrettyVersion($package) . ' (' . InstalledVersions::getReference($package) . ')';
        }

        return $stamp . "\n";
    }

    /**
     * The installed package with the deepest install path containing $path. The root
     * package is left out: its install path is the project itself.
     */
    private function findOwningPackage(string $path): ?string
    {
        $rootPackage = InstalledVersions::getRootPackage()['name'];
        $owner = null;
        $ownerPathLength = 0;

        foreach (InstalledVersions::getInstalledPackages() as $package) {
            if ($package === $rootPackage) {
                continue;
            }

            $installPath = InstalledVersions::getInstallPath($package);
            $installPath = $installPath !== null ? realpath($installPath) : false;
            if ($installPath === false) {
                continue;
            }

            if (str_starts_with($path . '/', $installPath . '/') && \strlen($installPath) > $ownerPathLength) {
                $owner = $package;
                $ownerPathLength = \strlen($installPath);
            }
        }

        return $owner;
    }
}
