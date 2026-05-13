<?php

namespace Kiwi\Contao\BootstrapBundle\CacheWarmer;

use Contao\CoreBundle\Framework\ContaoFramework;
use Kiwi\Contao\BootstrapBundle\Service\SpacingsFileRegenerator;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

class SpacingsCacheWarmer implements CacheWarmerInterface
{
    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly SpacingsFileRegenerator $regenerator,
    ) {}

    public function isOptional(): bool
    {
        return true;
    }

    public function warmUp(string $cacheDir, ?string $buildDir = null): array
    {
        try {
            // Ensure $GLOBALS['responsive']['config'] is populated by Contao's
            // config bootstrap before we ask the service to render.
            $this->framework->initialize();
            $this->regenerator->regenerate();
        } catch (\Throwable $e) {
            // Non-fatal: this warmer is optional. Matches TwbsAssetSymlinkWarmer's stance.
        }

        return [];
    }
}
