<?php

namespace Kiwi\Contao\Bootstrap\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Kiwi\Contao\Bootstrap\KiwiBootstrapBundle;
use Kiwi\Contao\ResponsiveBase\KiwiResponsiveBaseBundle;

class Plugin implements BundlePluginInterface
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
}
