<?php

namespace Kiwi\Contao\BootstrapBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Kiwi\Contao\BootstrapBundle\DependencyInjection\Compiler\OverrideServiceCompilerPass;

class KiwiBootstrapBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new OverrideServiceCompilerPass());
    }
}
