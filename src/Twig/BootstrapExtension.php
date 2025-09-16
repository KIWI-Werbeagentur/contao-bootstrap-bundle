<?php

namespace Kiwi\Contao\BootstrapBundle\Twig;

use Contao\CoreBundle\Image\Studio\Studio;
use Kiwi\Contao\BootstrapBundle\Util\BootstrapHelper;
use Kiwi\Contao\BootstrapBundle\Util\ResponsiveBackgroundImageHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class BootstrapExtension extends AbstractExtension
{
    public function __construct(
        protected Studio $studio,
    ) {}

    public function getFilters(): array
    {
        return [
            new TwigFilter('getBootstrapColumnClasses', [$this, 'getBootstrapColumnClasses']),
            new TwigFilter('getBootstrapButtonClasses', [$this, 'getBootstrapButtonClasses']),
            new TwigFilter('getBackgroundImageStyleTag', [$this, 'getBackgroundImageStyleTag']),
            new TwigFilter('getBootstrapContainerClasses', [$this, 'getBootstrapContainerClasses']),
            new TwigFilter('getInsideClasses', [$this, 'getInsideClasses']),
        ];
    }

    public function getBootstrapColumnClasses(array $arrData): array
    {
        $arrClasses = [];

        // Füge Bootstrap-Klassen zum Template hinzu, wenn die Einstellung im parent überschrieben werden soll
        if ($arrData['overwriteDefaultColumns'] && false !== strpos($GLOBALS['TL_DCA']['tl_content']['palettes'][$arrData['type']], 'overwriteDefaultColumns')) {
            $arrColClasses = BootstrapHelper::parseBootstrapClasses($arrData['col']);
            $arrBootstrapClasses = array_values($arrColClasses?:[]);
            $arrClasses = array_merge($arrClasses, $arrBootstrapClasses);
        }

        return $arrClasses;
    }

    public function getBootstrapButtonClasses(array $arrData): array
    {
        $arrClasses = [];

        if ('hyperlink' === $arrData['type']) {
            if (!empty($arrData['hyperlink_button'])) {
                $arrClasses[] = 'btn';
                $arrClasses[] = !empty($arrData['hyperlinkButtonStyle']) ? $arrData['hyperlinkButtonStyle'] : 'btn-primary';
            }
        }

        return $arrClasses;
    }

    public function getBackgroundImageStyleTag(array $arrData): string
    {
        $figure = $this->studio->createFigureBuilder()
            ->fromUuid($arrData['singleSRC'])
            ->setSize($arrData['size'])
            ->buildIfResourceExists();

        if (!$figure) return '';

        $GLOBALS['TL_CSS'][] = 'bundles/kiwiwrapper/wrapperbackgroundimage.scss|static';

        return ResponsiveBackgroundImageHelper::generateStyleTag($figure, $arrData['type'] . '-' . $arrData['id']);
    }

    public function getBootstrapContainerClasses(array $arrData): array
    {
        $arrClasses = [];

        if (!empty($arrData['addBootstrapContainer'])) {
            $containerClass = $arrData['bootstrap_container_width'];
            if ('container' === $arrData['bootstrap_container_width'] && !empty($arrData['bootstrap_container_breakpoint'])) {
                $containerClass .= '-' . $arrData['bootstrap_container_breakpoint'];
            }
            $arrClasses[] = $containerClass;
        }

        return $arrClasses;
    }

    public function getInsideClasses(array $arrData): array
    {
        $arrClasses = [];

        if (!empty($arrData['addBackgroundImage']) && !empty($arrData['gridSizedBackgroundImage'])) {
            $arrClasses[] = 'in-grid-bg';
        }

        $arrClasses = array_merge($arrClasses, array_values(BootstrapHelper::parseBootstrapClasses($arrData['bootstrap_default_columns'], true)));
        $arrClasses = array_merge($arrClasses, array_values(BootstrapHelper::getBootstrapFlexClasses($arrData['id'], 'tl_content', 'bootstrap_flex_direction')));
        $arrClasses = array_merge($arrClasses, array_values(BootstrapHelper::getBootstrapFlexClasses($arrData['id'], 'tl_content', 'bootstrap_flex_wrap')));
        $arrClasses = array_merge($arrClasses, array_values(BootstrapHelper::getBootstrapFlexClasses($arrData['id'], 'tl_content', 'bootstrap_flex_justify_content')));
        $arrClasses = array_merge($arrClasses, array_values(BootstrapHelper::getBootstrapFlexClasses($arrData['id'], 'tl_content', 'bootstrap_flex_align_items')));
        $arrClasses = array_merge($arrClasses, array_values(BootstrapHelper::getBootstrapFlexClasses($arrData['id'], 'tl_content', 'bootstrap_flex_align_content')));

        return $arrClasses;
    }
}
