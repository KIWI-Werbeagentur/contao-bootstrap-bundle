<?php

namespace Kiwi\Contao\BootstrapBundle\DependencyInjection\Compiler;

use Kiwi\Contao\BootstrapBundle\Migration\DelegatedModuleColumnsMigration;
use Kiwi\Contao\BootstrapBundle\Service\BootstrapFrontendService;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $defNewService = $container->getDefinition('kiwi.contao.responsive.frontend');
        $defNewService->setClass(BootstrapFrontendService::class);

        // The delegated-module column repair gains this bundle's responsiveOverwriteRowCols,
        // which on tl_content is the selector responsiveCols and responsiveOffsets live behind -
        // one setting, so one migration has to write all three together. Swapping the class
        // keeps it a single migration instead of two writing halves of it.
        if ($container->hasDefinition('kiwi.contao.responsive.migration.delegated_module_columns')) {
            $container->getDefinition('kiwi.contao.responsive.migration.delegated_module_columns')
                ->setClass(DelegatedModuleColumnsMigration::class);
        }
    }
}
