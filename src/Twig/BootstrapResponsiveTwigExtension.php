<?php

namespace Kiwi\Contao\BootstrapBundle\Twig;

use Kiwi\Contao\BootstrapBundle\Service\BootstrapFrontendService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BootstrapResponsiveTwigExtension extends AbstractExtension
{
    public function __construct(private BootstrapFrontendService $bootstrapFrontend)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getGutterClasses', [$this->bootstrapFrontend, 'getGutterClasses']),
        ];
    }
}
