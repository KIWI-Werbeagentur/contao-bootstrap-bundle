<?php

namespace Kiwi\Contao\BootstrapBundle\CacheWarmer;

use Contao\CoreBundle\Framework\ContaoFramework;
use Kiwi\Contao\BootstrapBundle\Service\GridStylesRegenerator;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

class GridStylesCacheWarmer implements CacheWarmerInterface
{
    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly GridStylesRegenerator $regenerator,
    ) {}

    public function isOptional(): bool
    {
        return true;
    }

    public function warmUp(string $cacheDir, ?string $buildDir = null): array
    {
        try {
            // Initialize the framework so $GLOBALS['responsive']['config'] (and any
            // project breakpoint override) is available before we render.
            $this->framework->initialize();
            $this->regenerator->regenerate();
        } catch (\Throwable $e) {
            // Non-fatal: this warmer is optional. Matches SpacingsCacheWarmer's stance.
        }

        return [];
    }
}
