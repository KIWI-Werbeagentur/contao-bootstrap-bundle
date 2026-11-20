<?php

namespace Kiwi\Contao\BootstrapBundle\CacheWarmer;

use Contao\CoreBundle\Framework\ContaoFramework;
use Kiwi\Contao\BootstrapBundle\Service\SpacingsFileRegenerator;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

class SpacingsCacheWarmer implements CacheWarmerInterface
{
    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly SpacingsFileRegenerator $regenerator,
        private readonly ?LoggerInterface $logger = null,
    ) {}

    public function isOptional(): bool
    {
        return true;
    }

    public function warmUp(string $cacheDir, ?string $buildDir = null): array
    {
        // Non-fatal either way: this warmer is optional, and the next cache:clear /
        // cache:warmup renders and compares again, so a skipped file is retried then.
        try {
            // Ensure $GLOBALS['responsive']['config'] is populated by Contao's
            // config bootstrap before we ask the service to render.
            $this->framework->initialize();
        } catch (\Throwable $e) {
            // Expected where no database or Contao setup is available (e.g. a build step).
            $this->logger?->warning('Skipped regenerating the spacings SCSS: the Contao framework could not be initialized.', ['exception' => $e]);

            return [];
        }

        try {
            $this->regenerator->regenerate();
        } catch (\Throwable $e) {
            // The file keeps its previous content until the next successful run.
            $this->logger?->error('Failed to regenerate the spacings SCSS; it stays at its previous state: {message}', ['message' => $e->getMessage(), 'exception' => $e]);
        }

        return [];
    }
}
