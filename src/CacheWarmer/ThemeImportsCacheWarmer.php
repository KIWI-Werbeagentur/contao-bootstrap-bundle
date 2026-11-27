<?php

namespace Kiwi\Contao\BootstrapBundle\CacheWarmer;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\ThemeModel;
use Kiwi\Contao\BootstrapBundle\Service\ThemeImportsFileRegenerator;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

/**
 * Re-renders every theme's `_imports-<theme>.scss` during `cache:warmup`, so a changed
 * component list or bootstrap path reaches existing themes without saving them.
 *
 * Mirrors {@see LayoutImportsCacheWarmer}: optional and non-fatal.
 */
class ThemeImportsCacheWarmer implements CacheWarmerInterface
{
    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly ThemeImportsFileRegenerator $regenerator,
        private readonly ?LoggerInterface $logger = null,
    ) {}

    public function isOptional(): bool
    {
        return true;
    }

    public function warmUp(string $cacheDir, ?string $buildDir = null): array
    {
        try {
            // Populates $GLOBALS['responsive'] and makes the models usable.
            $this->framework->initialize();

            $themes = ThemeModel::findAll();
            if ($themes === null) {
                return [];
            }

            foreach ($themes as $theme) {
                $themeAlias = (string) ($theme->alias ?? '');
                if ($themeAlias === '') {
                    continue;
                }

                // Per theme, so one unwritable file does not stop the remaining themes.
                try {
                    $this->regenerator->regenerate($themeAlias, $theme->responsiveBootstrapComponents);
                } catch (\Throwable $e) {
                    // The file keeps its previous content until the next successful run.
                    $this->logger?->error('Failed to regenerate the imports SCSS of theme "{theme}"; it stays at its previous state: {message}', ['theme' => $themeAlias, 'message' => $e->getMessage(), 'exception' => $e]);
                }
            }
        } catch (\Throwable $e) {
            // Framework / database not available (e.g. a build step without a database).
            $this->logger?->warning('Skipped regenerating the theme imports SCSS: the themes could not be loaded.', ['exception' => $e]);
        }

        return [];
    }
}
