<?php

namespace Kiwi\Contao\BootstrapBundle\Service;

use Kiwi\Contao\BootstrapBundle\Configuration\BootstrapConfiguration;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

class SpacingsFileRegenerator
{
    private const TWIG_TEMPLATE       = '@Contao/responsive/spacings.scss.twig';
    private const RELATIVE_TARGET_DIR = '/files/themes/';
    private const TARGET_FILENAME     = '_spacings.scss';

    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly Environment $twig,
        private readonly string $projectDir,
    ) {}

    /**
     * Render the spacings SCSS from the Twig template and write it to disk
     * only when the rendered output differs from the current file.
     *
     * @return bool true if the file was (re)written, false on no-op.
     */
    public function regenerate(): bool
    {
        $targetDir  = $this->projectDir . self::RELATIVE_TARGET_DIR;
        $targetFile = $targetDir . self::TARGET_FILENAME;

        if (!$this->filesystem->exists($targetDir)) {
            $this->filesystem->mkdir($targetDir);
        }

        // Honor project-level override of the responsive config; fall back to
        // this bundle's BootstrapConfiguration when Contao globals are not yet
        // initialized.
        $configClass = $GLOBALS['responsive']['config'] ?? BootstrapConfiguration::class;
        $sizes       = implode(', ', (new $configClass())->getSpacingsExcludingNoOp());

        $rendered = $this->twig->render(self::TWIG_TEMPLATE, ['sizes' => $sizes]);
        $current  = $this->filesystem->exists($targetFile) ? file_get_contents($targetFile) : null;

        if ($current === $rendered) {
            return false;
        }

        file_put_contents($targetFile, $rendered);

        return true;
    }
}
