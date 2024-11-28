<?php

namespace Kiwi\Contao\BootstrapBundle\Configuration;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Kiwi\Contao\ResponsiveBaseBundle\Configuration\ResponsiveConfiguration;

class BootstrapConfiguration extends ResponsiveConfiguration
{

    protected array $arrBreakpoints = [
        'xs' => ['breakpoint' => 0, 'modifier' => ''],
        'sm' => ['breakpoint' => 460, 'modifier' => '-sm'],
        'md' => ['breakpoint' => 768, 'modifier' => '-md'],
        'lg' => ['breakpoint' => 992, 'modifier' => '-lg'],
        'xl' => ['breakpoint' => 1024, 'modifier' => '-xl'],
        'xxl' => ['breakpoint' => 2048, 'modifier' => '-xxl'],
    ];
    protected array $arrCols = [
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

    protected array $arrColsDefaults = ['xs' => 12, 'lg' => 6];

    protected array $arrOffsets = [
        'none' => 'offset{{breakpoint}}-none',
        'auto' => 'offset{{breakpoint}}-auto',
        1 => 'offset{{breakpoint}}-1',
        2 => 'offset{{breakpoint}}-2',
        3 => 'offset{{breakpoint}}-3',
        4 => 'offset{{breakpoint}}-4',
        5 => 'offset{{breakpoint}}-5',
        6 => 'offset{{breakpoint}}-6',
        7 => 'offset{{breakpoint}}-7',
        8 => 'offset{{breakpoint}}-8',
        9 => 'offset{{breakpoint}}-9',
        10 => 'offset{{breakpoint}}-10',
        11 => 'offset{{breakpoint}}-11',
        12 => 'offset{{breakpoint}}-12',
    ];

    protected array $arrOffsetsDefaults = ['xs' => 'none'];

    protected array $arrSpacings = [
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

    #[AsCallback(table: 'tl_article', target: 'config.onload')]
    #[AsCallback(table: 'tl_content', target: 'config.onload')]
    public function getDefaults(): void
    {
        $GLOBALS['TL_DCA']['tl_article']['fields']['responsiveRowCols']['default'] = $this->arrRowColsDefaults;
        parent::getDefaults();
    }
}