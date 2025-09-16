<?php

namespace Kiwi\Contao\BootstrapBundle;

use Contao\CalendarBundle\ContaoCalendarBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use Contao\NewsBundle\ContaoNewsBundle;
use Symfony\Component\Config\Loader\LoaderInterface;

class ContaoManagerPlugin implements BundlePluginInterface, ConfigPluginInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function getBundles(ParserInterface $parser)
	{
        $arrLoadAfter = [
            ContaoCoreBundle::class,
            ContaoNewsBundle::class,
            ContaoCalendarBundle::class,
        ];

		return [
			BundleConfig::create(KiwiBootstrapBundle::class)
				->setLoadAfter($arrLoadAfter)
				->setReplace(['xt_bootstrap']),
		];
	}

    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig)
    {
        $loader->load(__DIR__ . '/Resources/config/services.yaml');
    }
}
