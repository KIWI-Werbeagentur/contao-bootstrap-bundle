<?php

namespace Kiwi\Contao\BootstrapBundle\CacheWarmer;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

class TwbsAssetSymlinkWarmer implements CacheWarmerInterface
{
    private const LINK   = 'assets/kiwi-twbs-bootstrap';
    private const TARGET = '../vendor/twbs/bootstrap/dist';

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var string
     */
    protected $projectDir;

    public function __construct(Filesystem $filesystem, string $projectDir)
    {
        $this->filesystem = $filesystem;
        $this->projectDir = $projectDir;
    }

    public function isOptional(): bool
    {
        return true;
    }

    public function warmUp(string $cacheDir, ?string $buildDir = null): array
    {
        $link = $this->projectDir . '/' . self::LINK;

        if (!is_dir($this->projectDir . '/vendor/twbs/bootstrap/dist')) {
            return [];
        }

        if (is_link($link) && readlink($link) === self::TARGET) {
            return [];
        }

        try {
            $this->filesystem->remove($link);
            $this->filesystem->symlink(self::TARGET, $link);
        } catch (IOException $e) {
            // non-fatal; isOptional() covers us
        }

        return [];
    }
}
