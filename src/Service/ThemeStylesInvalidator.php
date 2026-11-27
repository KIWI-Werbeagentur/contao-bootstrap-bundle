<?php

namespace Kiwi\Contao\BootstrapBundle\Service;

use Contao\Automator;
use Contao\CoreBundle\Framework\ContaoFramework;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Makes Contao recompile the layout stylesheets after a generated SCSS partial changed.
 *
 * Outside debug mode the Combiner compiles a SCSS file only while its compiled copy in
 * assets/css is missing, and derives the ?v= value (and the combined file's key) from the
 * filemtime() of the top-level file alone. A change further down the import chain is
 * therefore invisible to it. Touching each `layout-<layout>.scss` bumps the version, and
 * purging the script cache removes the stale compiled files.
 */
class ThemeStylesInvalidator
{
    private const THEMES_DIR = '/files/themes/';

    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly Filesystem $filesystem,
        private readonly string $projectDir,
        private readonly ?LoggerInterface $logger = null,
    ) {}

    /**
     * With both aliases one layout is invalidated, with only the theme alias all layouts of
     * that theme, with neither all layouts. Failures are logged, not thrown: the partial
     * has already been written at this point.
     */
    public function invalidate(?string $themeAlias = null, ?string $layoutAlias = null): void
    {
        try {
            $pattern = $this->projectDir . self::THEMES_DIR . ($themeAlias ?? '*') . '/' . ($layoutAlias ?? '*') . '/layout-' . ($layoutAlias ?? '*') . '.scss';

            foreach (glob($pattern) ?: [] as $file) {
                // The wildcards match layout-a.scss in folder b as well; only layout-<folder>.scss is a layout's entry file.
                if (basename($file) !== 'layout-' . basename(\dirname($file)) . '.scss') {
                    continue;
                }

                $this->filesystem->touch($file);
            }

            $this->framework->initialize();
            $this->framework->createInstance(Automator::class)->purgeScriptCache();
        } catch (\Throwable $e) {
            $this->logger?->error('Failed to invalidate the compiled theme styles: {message}', ['message' => $e->getMessage(), 'exception' => $e]);
        }
    }
}
