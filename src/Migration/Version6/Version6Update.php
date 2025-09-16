<?php

namespace Kiwi\Contao\BootstrapBundle\Migration\Version6;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Symfony\Component\Filesystem\Filesystem;

class Version6Update extends AbstractMigration
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $projectDir;

    /**
     * @var string
     */
    private $targetPath = "var/bootstrap/";

    public function __construct(Filesystem $filesystem, string $projectDir)
    {
        $this->filesystem = $filesystem;
        $this->projectDir = $projectDir;
    }

    public function getName(): string
    {
        return "BootstrapBundle Version 6 Update";
    }

    public function shouldRun(): bool
    {
        if ($this->filesystem->exists($this->targetPath . 'custom-bootstrap.scss')) {
            $content = file_get_contents($this->targetPath . 'custom-bootstrap.scss');
            if (strpos($content, "* Bootstrap v5") !== -1) {
                return false;
            }
        } else {
            return false;
        }

        return true;
    }

    public function run(): MigrationResult
    {
        $vars = "custom-variables.scss";
        $bs = "custom-bootstrap.scss";

        if ($this->filesystem->exists($this->targetPath)) {
            if ($this->filesystem->exists($this->targetPath . $vars)) {
                $this->filesystem->remove($this->targetPath . $vars);
                $this->filesystem->copy('vendor/kiwi/contao-bootstrap-bundle/src/Resources/customization/custom-variables.scss.dist', $this->targetPath . $vars, false);
            }
            if ($this->filesystem->exists($this->targetPath . $bs)) {
                $this->filesystem->remove($this->targetPath . $bs);
                $this->filesystem->copy('vendor/kiwi/contao-bootstrap-bundle/src/Resources/customization/custom-bootstrap.scss.dist', $this->targetPath . $bs, false);
            }

            $rootToVendorPath = $this->filesystem->makePathRelative($this->projectDir.'/vendor/', realpath('.'));
            $content = file_get_contents($this->targetPath . 'custom-bootstrap.scss');
            $content = str_replace('~RESOURCEPATH~', $rootToVendorPath.'twbs/bootstrap/scss', $content);
            $content = str_replace('~KIWIRESOURCEPATH~', $rootToVendorPath.'kiwi/contao-bootstrap-bundle/src/Resources/scss', $content);
            file_put_contents($this->targetPath . 'custom-bootstrap.scss', $content);
        }
        return $this->createResult(true);
    }
}