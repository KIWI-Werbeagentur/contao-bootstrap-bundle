<?php

namespace Kiwi\Contao\BootstrapBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use Kiwi\Contao\BootstrapBundle\KiwiBootstrapBundle;
use Kiwi\Contao\ResponsiveBaseBundle\KiwiResponsiveBaseBundle;
use Symfony\Component\Config\Loader\LoaderInterface;

class Plugin implements BundlePluginInterface, ConfigPluginInterface
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

    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig): void
    {
        $loader->load(__DIR__ . '/../../config/services.yaml');
        $loader->load(__DIR__ . '/../../config/migrations.yaml');
    }
}
