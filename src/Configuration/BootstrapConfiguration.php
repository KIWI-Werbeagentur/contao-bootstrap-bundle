<?php

namespace Kiwi\Contao\BootstrapBundle\Configuration;

use Contao\DataContainer;
use Kiwi\Contao\ResponsiveBaseBundle\Configuration\ResponsiveConfiguration;

class BootstrapConfiguration extends ResponsiveConfiguration
{
    /** Menu token; emits .kiwi-gutter-x-default-{bp} only where it needs to override a prior gx-* size. */
    public const GUTTER_DEFAULT = 'default';

    // TO DO: SHELL COMMAND TO CREATE/UPDATE SCSS FILE
    protected array $arrBreakpoints = [
        'xs' => ['breakpoint' => 0, 'modifier' => '', 'container' => '100%'],
        'sm' => ['breakpoint' => 576, 'modifier' => '-sm', 'container' => '540px'],
        'md' => ['breakpoint' => 768, 'modifier' => '-md', 'container' => '720px'],
        'lg' => ['breakpoint' => 992, 'modifier' => '-lg', 'container' => '960px'],
        'xl' => ['breakpoint' => 1200, 'modifier' => '-xl', 'container' => '1140px'],
        'xxl' => ['breakpoint' => 1400, 'modifier' => '-xxl', 'container' => '1320px'],
    ];

    protected array $arrContainerSizes = [
        'container-fluid' => 'container-fluid',
        'container' => 'container',
        'container-sm' => 'container-sm',
        'container-md' => 'container-md',
        'container-lg' => 'container-lg',
        'container-xl' => 'container-xl',
        'container-xxl' => 'container-xxl',
    ];

    protected string $strContainerDefault = 'container';
    protected string $strContainerDefaultLayout = 'container-fluid';

    protected string $strRow = 'row';

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
        'hidden' => 'd{{modifier}}-none-only',
    ];

    protected array $arrColsDefaults = ['xs' => 12];

    protected array $arrOffsets = [
        'none' => 'offset{{modifier}}-0',
        'auto' => 'ms{{modifier}}-auto',
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

    protected array $arrOffsetsDefaults = ['xs' => 'none'];

    protected array $arrSpacings = [
        'default' => 'p{{direction}}{{modifier}}-default',
        'none' => 'p{{direction}}{{modifier}}-none',
        'gap' => 'p{{direction}}{{modifier}}-gap',
        'gap-half' => 'p{{direction}}{{modifier}}-gap-half',
        'xxs' => 'p{{direction}}{{modifier}}-xxs',
        'xs' => 'p{{direction}}{{modifier}}-xs',
        'sm' => 'p{{direction}}{{modifier}}-sm',
        'md' => 'p{{direction}}{{modifier}}-md',
        'lg' => 'p{{direction}}{{modifier}}-lg',
        'xl' => 'p{{direction}}{{modifier}}-xl',
        'xxl' => 'p{{direction}}{{modifier}}-xxl',
    ];

    protected array $arrSpacingsDefaults = ['xs' => 'default'];
    protected array $arrSpacingTopDefaults = ['xs' => 'default'];
    protected array $arrSpacingBottomDefaults = ['xs' => 'default'];

    protected array|string $varOrderClasses = [];

    protected array|string $varAlignSelfClasses = [];

    protected array|string $varFlexDirectionClasses = [];

    protected array|string $varJustifyContentClasses = [];

    protected array|string $varAlignItemsClasses = [];

    protected array|string $varAlignContentClasses = [];

    protected array|string $varFlexWrapClasses = [];

    protected array $arrRowCols = [
        'auto' => 'row-cols{{modifier}}-auto',
        1 => 'row-cols{{modifier}}-1',
        2 => 'row-cols{{modifier}}-2',
        3 => 'row-cols{{modifier}}-3',
        4 => 'row-cols{{modifier}}-4',
        5 => 'row-cols{{modifier}}-5',
        6 => 'row-cols{{modifier}}-6',
    ];

    protected array|string $varRowColsClasses = [];

    /**
     * Full enumeration of all gutter tokens to their class templates.
     * Order is preserved in the BE menu via {@see self::getGutterSizeKeys()}.
     * Projects extending the available spacers override this property (and the SCSS `$gutters` map).
     *
     * @var array<string, string>
     */
    protected array $arrGutterClasses = [
        self::GUTTER_DEFAULT => 'kiwi-gutter-x-default{{modifier}}',
        '0'   => 'gx{{modifier}}-0',
        '1'   => 'gx{{modifier}}-1',
        '2'   => 'gx{{modifier}}-2',
        '3'   => 'gx{{modifier}}-3',
        '4'   => 'gx{{modifier}}-4',
        '5'   => 'gx{{modifier}}-5',
        '6'   => 'gx{{modifier}}-6',
        '7'   => 'gx{{modifier}}-7',
        '8'   => 'gx{{modifier}}-8',
        '9'   => 'gx{{modifier}}-9',
        '10'  => 'gx{{modifier}}-10',
    ];

    /**
     * Default gutter selection per breakpoint.
     *
     * @var array<string, int|string>
     */
    protected array $arrGutterDefaults = ['xs' => self::GUTTER_DEFAULT];

    public function __construct($objDca = null)
    {
        parent::__construct($objDca);

        $this->arrAlignmentContent = [
            'normal' => 'normal',
            'start' => 'start',
            'center' => 'center',
            'end' => 'end',
            'between' => 'between',
            'around' => 'around',
            'evenly' => 'evenly'
        ];

        $this->arrIcons['alignContent']['around'] = "/bundles/kiwiresponsivebase/icons/align-content/flex-content-space-around.svg";
        $this->arrIcons['alignContent']['evenly'] = "/bundles/kiwiresponsivebase/icons/align-content/flex-content-space-evenly.svg";
        $this->arrIcons['alignContent']['between'] = "/bundles/kiwiresponsivebase/icons/align-content/flex-content-space-between.svg";

        $this->arrIcons['justifyContent']['around'] = "/bundles/kiwiresponsivebase/icons/justify-content/flex-content-space-around.svg";
        $this->arrIcons['justifyContent']['evenly'] = "/bundles/kiwiresponsivebase/icons/justify-content/flex-content-space-evenly.svg";
        $this->arrIcons['justifyContent']['between'] = "/bundles/kiwiresponsivebase/icons/justify-content/flex-content-space-between.svg";
    }

    public function __get(string $name)
    {
        return match ($name) {
            'varOrderClasses' => "order{{modifier}}-{{value}}",
            'varAlignSelfClasses' => "align-self{{modifier}}-{{value}}",
            'varFlexDirectionClasses' => "flex{{modifier}}-{{value}}",
            'varJustifyContentClasses' => "justify-content{{modifier}}-{{value}}",
            'varAlignItemsClasses' => "align-items{{modifier}}-{{value}}",
            'varAlignContentClasses' => "align-content{{modifier}}-{{value}}",
            'varFlexWrapClasses' => "flex{{modifier}}-{{value}}",
            'varRowColsClasses' => $this->arrRowCols,
            'varGutterClasses' => $this->arrGutterClasses,
            default => parent::__get($name),
        };
    }

    public function getRowCols(): array
    {
        return array_keys($this->arrRowCols);
    }

    protected array $arrRowColsDefaults = ['xs' => 1];

    public function getGutterSizeKeys(): array
    {
        return array_keys($this->arrGutterClasses);
    }

    public function getDefaults(DataContainer $objDca): void
    {
        if (isset($GLOBALS['TL_DCA'][$objDca->table]['fields']['responsiveRowCols'])) {
            $GLOBALS['TL_DCA'][$objDca->table]['fields']['responsiveRowCols']['default'] = $this->arrRowColsDefaults;
        }

        $gutterDefault = serialize($this->arrGutterDefaults);
        foreach (['responsiveGutter', 'responsiveGutterHeader', 'responsiveGutterFooter'] as $field) {
            if (isset($GLOBALS['TL_DCA'][$objDca->table]['fields'][$field])) {
                $GLOBALS['TL_DCA'][$objDca->table]['fields'][$field]['default'] = $gutterDefault;
            }
        }

        parent::getDefaults($objDca);
    }
}
