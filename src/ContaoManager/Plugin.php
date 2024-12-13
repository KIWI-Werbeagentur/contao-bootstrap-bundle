<?php

namespace Kiwi\Contao\BootstrapBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Kiwi\Contao\BootstrapBundle\KiwiBootstrapBundle;
use Kiwi\Contao\ResponsiveBaseBundle\KiwiResponsiveBaseBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class Plugin implements BundlePluginInterface, RoutingPluginInterface, ConfigPluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(KiwiBootstrapBundle::class)
                ->setLoadAfter([
                    ContaoCoreBundle::class,
                    KiwiResponsiveBaseBundle::class
                ])
        ];
    }

    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel)
    {
        return $resolver
            ->resolve(__DIR__ . '/../../config/routes.yaml')
            ->load(__DIR__ . '/../../config/routes.yaml')
            ;
    }

    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig): void
    {
        $loader->load(__DIR__ . '/../../config/services.yaml');
    }
}
