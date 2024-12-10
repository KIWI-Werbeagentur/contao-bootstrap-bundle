<?php

namespace Kiwi\Contao\BootstrapBundle\Configuration;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Kiwi\Contao\ResponsiveBaseBundle\Configuration\ResponsiveConfiguration;

class BootstrapConfiguration extends ResponsiveConfiguration
{
    public array $arrBreakpoints = [
        'xs' => ['breakpoint' => 0, 'modifier' => ''],
        'sm' => ['breakpoint' => 460, 'modifier' => '-sm'],
        'md' => ['breakpoint' => 768, 'modifier' => '-md'],
        'lg' => ['breakpoint' => 992, 'modifier' => '-lg'],
        'xl' => ['breakpoint' => 1024, 'modifier' => '-xl'],
        'xxl' => ['breakpoint' => 2048, 'modifier' => '-xxl'],
    ];

    public array $arrContainerSizes = [
        'container-fluid' => 'container-fluid',
        'container' => 'container',
        'container-sm' => 'container-sm',
        'container-md' => 'container-md',
        'container-lg' => 'container-lg',
        'container-xl' => 'container-xl',
        'container-xxl' => 'container-xxl',
    ];

    public string $strContainerDefault = 'container';

    public array $arrCols = [
        12 => 'col{{modifier}}-12',
        11 => 'col{{modifier}}-11',
        10 => 'col{{modifier}}-10',
        9 => 'col{{modifier}}-9',
        8 => 'col{{modifier}}-8',
        7 => 'col{{modifier}}-7',
        6 => 'col{{modifier}}-6',
        5 => 'col{{modifier}}-5',
        4 => 'col{{modifier}}-4',
        3 => 'col{{modifier}}-3',
        2 => 'col{{modifier}}-2',
        1 => 'col{{modifier}}-1',
        'auto' => 'col{{modifier}}-auto',
        'fill' => 'col{{modifier}}',
        'hidden' => 'col{{modifier}}-none',
    ];

    public array $arrColsDefaults = ['xs' => 12, 'lg' => 6];

    public array $arrOffsets = [
        'none' => 'offset{{modifier}}-none',
        'auto' => 'offset{{modifier}}-auto',
        1 => 'offset{{modifier}}-1',
        2 => 'offset{{modifier}}-2',
        3 => 'offset{{modifier}}-3',
        4 => 'offset{{modifier}}-4',
        5 => 'offset{{modifier}}-5',
        6 => 'offset{{modifier}}-6',
        7 => 'offset{{modifier}}-7',
        8 => 'offset{{modifier}}-8',
        9 => 'offset{{modifier}}-9',
        10 => 'offset{{modifier}}-10',
        11 => 'offset{{modifier}}-11',
        12 => 'offset{{modifier}}-12',
    ];

    public array $arrOffsetsDefaults = ['xs' => 'none'];

    public array $arrSpacings = [
        'default' => 'p{{direction}}{{modifier}}-default',
        'none' => 'p{{direction}}{{modifier}}-none',
        'gap' => 'p{{direction}}{{modifier}}-gap',
        'gap-full' => 'p{{direction}}{{modifier}}-gap-full',
        'xs' => 'p{{direction}}{{modifier}}-xs',
        'sm' => 'p{{direction}}{{modifier}}-sm',
        'md' => 'p{{direction}}{{modifier}}-md',
        'lg' => 'p{{direction}}{{modifier}}-lg',
        'xl' => 'p{{direction}}{{modifier}}-xl',
    ];

    public array $arrSpacingsDefaults = [];

    protected array $arrRowCols = [
        'auto' => 'row-cols{{modifier}}-auto',
        1 => 'row-cols{{modifier}}-1',
        2 => 'row-cols{{modifier}}-2',
        3 => 'row-cols{{modifier}}-3',
        4 => 'row-cols{{modifier}}-4',
        5 => 'row-cols{{modifier}}-5',
        6 => 'row-cols{{modifier}}-6',
    ];

    protected array $arrRowColsDefaults = ['xs' => 1, 'lg' => 2];

    public function getRowCols(): array
    {
        return array_keys($this->arrRowCols);
    }

    #[AsCallback(table: 'tl_module', target: 'config.onload')]
    #[AsCallback(table: 'tl_theme', target: 'config.onload')]
    #[AsCallback(table: 'tl_layout', target: 'config.onload')]
    #[AsCallback(table: 'tl_article', target: 'config.onload')]
    #[AsCallback(table: 'tl_content', target: 'config.onload')]
    #[AsCallback(table: 'tl_form', target: 'config.onload')]
    #[AsCallback(table: 'tl_form_field', target: 'config.onload')]
    public function getDefaults(DataContainer $objDca): void
    {
        $GLOBALS['TL_DCA'][$objDca->table]['fields']['responsiveRowCols']['default'] = (new $GLOBALS['responsive']['config'])->arrRowColsDefaults;
        parent::getDefaults($objDca);
    }
}