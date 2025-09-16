<?php

namespace Kiwi\Contao\BootstrapBundle\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class SymlinkJsMigration extends AbstractMigration
{
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

    public function shouldRun(): bool
    {
        if (!$this->filesystem->exists($this->projectDir . '/vendor/kiwi/contao-bootstrap-bundle/src/Resources/public/twbs-combined')) {
            return true;
        }

        return false;
    }

    public function run(): MigrationResult
    {
        try {
            $this->filesystem->symlink($this->projectDir . '/vendor/twbs/bootstrap/dist/js', $this->projectDir . '/vendor/kiwi/contao-bootstrap-bundle/src/Resources/public/twbs-combined');
        } catch (IOException $e) {
            return $this->createResult(false, $e->getMessage());
        }

        return $this->createResult(true);
    }
}
