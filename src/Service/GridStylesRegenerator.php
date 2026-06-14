<?php

namespace Kiwi\Contao\BootstrapBundle\Service;

use Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration;
use Kiwi\Contao\BootstrapBundle\Configuration\Grid\GridStyles;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

/**
 * Writes files/themes/_grid.scss: the semantic and default CSS custom properties
 * derived from the `kiwi_bootstrap.grid` configuration.
 *
 * The processed config ({@see GridStyles}) is the data source; the SCSS shape
 * (mixin + auto-include) lives in the @Contao/responsive/grid.scss.twig template.
 * With no grid configured the file is an empty, no-op mixin.
 */
class GridStylesRegenerator
{
    private const TWIG_TEMPLATE   = '@Contao/responsive/grid.scss.twig';
    private const RELATIVE_TARGET = '/files/themes/_grid.scss';

    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly Environment $twig,
        private readonly string $projectDir,
        private readonly array $grid,
    ) {}

    /**
     * @return bool true if the file was (re)written, false on no-op.
     */
    public function regenerate(): bool
    {
        $targetFile = $this->projectDir . self::RELATIVE_TARGET;
        $targetDir = \dirname($targetFile);

        if (!$this->filesystem->exists($targetDir)) {
            $this->filesystem->mkdir($targetDir);
        }

        $styles = new GridStyles($this->grid, $this->breakpointMinWidths());

        // Populate the SpacingScale dropdown union with the steps this config needs.
        $styles->registerRequiredSteps();

        $rendered = $this->twig->render(self::TWIG_TEMPLATE, [
            'baseVariables' => $styles->baseVariables(),
            'mediaGroups' => $styles->mediaVariableGroups(),
            'utilitySets' => $styles->utilityClassSets(),
        ]);

        $current = $this->filesystem->exists($targetFile) ? file_get_contents($targetFile) : null;

        if ($current === $rendered) {
            return false;
        }

        file_put_contents($targetFile, $rendered);

        return true;
    }

    /**
     * @return array<string, int>
     */
    private function breakpointMinWidths(): array
    {
        $configClass = $GLOBALS['responsive']['config'] ?? BootstrapConfiguration::class;
        $config = new $configClass();

        if (method_exists($config, 'getBreakpointMinWidths')) {
            return $config->getBreakpointMinWidths();
        }

        // Fallback to the bundle's standard breakpoints.
        return ['xs' => 0, 'sm' => 576, 'md' => 768, 'lg' => 992, 'xl' => 1200, 'xxl' => 1400];
    }
}
