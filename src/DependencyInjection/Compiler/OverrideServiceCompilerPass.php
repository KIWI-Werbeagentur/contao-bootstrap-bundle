<?php

namespace Kiwi\Contao\BootstrapBundle\DependencyInjection\Compiler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $defNewService = $container->getDefinition('kiwi.contao.responsive.frontend');
        $defNewService->setClass('\Kiwi\Contao\BootstrapBundle\Service\BootstrapFrontendService');
    }
}