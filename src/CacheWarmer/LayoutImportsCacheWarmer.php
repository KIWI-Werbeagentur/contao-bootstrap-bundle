<?php

namespace Kiwi\Contao\BootstrapBundle\CacheWarmer;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\LayoutModel;
use Contao\ThemeModel;
use Kiwi\Contao\BootstrapBundle\Service\LayoutImportsFileRegenerator;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

/**
 * Re-renders every layout's `_imports-<layout>.scss` during `cache:warmup`
 * (and thus implicitly after `cache:clear`), so bundle template updates
 * brought in by `composer update` propagate automatically without requiring
 * an editor to open and save each layout in the backend.
 *
 * Mirrors {@see SpacingsCacheWarmer}: optional, swallows all exceptions
 * (the framework / database may not be ready in every environment).
 */
class LayoutImportsCacheWarmer implements CacheWarmerInterface
{
    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly LayoutImportsFileRegenerator $regenerator,
    ) {}

    public function isOptional(): bool
    {
        return true;
    }

    public function warmUp(string $cacheDir, ?string $buildDir = null): array
    {
        try {
            // Ensures `$GLOBALS['responsive']['bootstrap']` etc. and the Contao
            // model classes are available — they are populated by Contao's
            // config bootstrap during framework initialization.
            $this->framework->initialize();

            $layouts = LayoutModel::findAll();
            if ($layouts === null) {
                return [];
            }

            $themeAliases = [];

            foreach ($layouts as $layout) {
                $layoutAlias = (string) ($layout->alias ?? '');
                if ($layoutAlias === '') {
                    continue;
                }

                $themePid = (int) ($layout->pid ?? 0);
                if ($themePid <= 0) {
                    continue;
                }

                // Cache theme lookups so installations with many layouts per
                // theme don't issue one query per layout.
                if (!array_key_exists($themePid, $themeAliases)) {
                    $theme                   = ThemeModel::findByPk($themePid);
                    $themeAliases[$themePid] = $theme !== null ? (string) ($theme->alias ?? '') : '';
                }

                $themeAlias = $themeAliases[$themePid];
                if ($themeAlias === '') {
                    continue;
                }

                $this->regenerator->regenerate($themeAlias, $layoutAlias);
            }
        } catch (\Throwable $e) {
            // Non-fatal: matches SpacingsCacheWarmer's stance.
        }

        return [];
    }
}
