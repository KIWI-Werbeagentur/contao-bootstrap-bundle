<?php

namespace Kiwi\Contao\BootstrapBundle\Configuration;

use Contao\DataContainer;
use Kiwi\Contao\ResponsiveBaseBundle\Configuration\ResponsiveConfiguration;

class BootstrapConfiguration extends ResponsiveConfiguration
{
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
     * Holds the resolved per-breakpoint container-padding-x classes for the current element.
     * Populated by the responsive engine via the {@see self::__get()} mapping below.
     */
    protected array|string $varContainerPaddingXClasses = [];

    /**
     * Full enumeration of all gutter tokens to their class templates.
     * Order is preserved in the BE menu via {@see self::getGutterSizeKeys()}.
     * Projects extending the available spacers override this property (and the SCSS `$gutters` map).
     *
     * @var array<string, string>
     */
    protected array $arrGutterClasses = [
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
     * Default gutter selection per breakpoint for the main content area.
     *
     * @var array<string, int|string>
     */
    protected array $arrGutterDefaults = ['xs' => '4'];

    /**
     * Default gutter selection per breakpoint for the header section.
     *
     * @var array<string, int|string>
     */
    protected array $arrGutterHeaderDefaults = ['xs' => '4'];

    /**
     * Default gutter selection per breakpoint for the footer section.
     *
     * @var array<string, int|string>
     */
    protected array $arrGutterFooterDefaults = ['xs' => '4'];

    /**
     * Full enumeration of container-padding-x tokens to their class templates.
     * Maps to the .cx-{N} utilities emitted by grid-overrides.scss, which apply
     * the container's own outer L/R padding (opt-in, fluid-gated, non-inheriting)
     * independently from the inter-column gutter.
     *
     * There is intentionally no `default` token: container padding is fully
     * opt-in. An empty selection emits no class and the container has no
     * outer padding.
     *
     * @var array<string, string>
     */
    protected array $arrContainerPaddingXClasses = [
        '0'   => 'cx{{modifier}}-0',
        '1'   => 'cx{{modifier}}-1',
        '2'   => 'cx{{modifier}}-2',
        '3'   => 'cx{{modifier}}-3',
        '4'   => 'cx{{modifier}}-4',
        '5'   => 'cx{{modifier}}-5',
        '6'   => 'cx{{modifier}}-6',
        '7'   => 'cx{{modifier}}-7',
        '8'   => 'cx{{modifier}}-8',
        '9'   => 'cx{{modifier}}-9',
        '10'  => 'cx{{modifier}}-10',
    ];

    /**
     * Default container-padding-x selection per breakpoint.
     *
     * Override without subclassing via $GLOBALS['responsive']['containerPaddingXDefault']
     * (side key 'main'); see {@see self::applyConfiguredContainerPaddingXDefaults()}.
     *
     * @var array<string, int|string>
     */
    protected array $arrContainerPaddingXDefaults = ['xs' => '2'];

    /**
     * Default container-padding-x selection for the layout header section.
     * Override via the 'header' side key of $GLOBALS['responsive']['containerPaddingXDefault'].
     *
     * @var array<string, int|string>
     */
    protected array $arrContainerPaddingXHeaderDefaults = ['xs' => '2'];

    /**
     * Default container-padding-x selection for the layout footer section.
     * Override via the 'footer' side key of $GLOBALS['responsive']['containerPaddingXDefault'].
     *
     * @var array<string, int|string>
     */
    protected array $arrContainerPaddingXFooterDefaults = ['xs' => '2'];

    /**
     * Full enumeration of row-gap tokens to their class templates.
     * Maps to the row-gap-{N} utilities backported from Bootstrap 5.3 via extend-utilities.scss.
     *
     * @var array<string, string>
     */
    protected array $arrRowGapClasses = [
        '0'   => 'row-gap{{modifier}}-0',
        '1'   => 'row-gap{{modifier}}-1',
        '2'   => 'row-gap{{modifier}}-2',
        '3'   => 'row-gap{{modifier}}-3',
        '4'   => 'row-gap{{modifier}}-4',
        '5'   => 'row-gap{{modifier}}-5',
        '6'   => 'row-gap{{modifier}}-6',
        '7'   => 'row-gap{{modifier}}-7',
        '8'   => 'row-gap{{modifier}}-8',
        '9'   => 'row-gap{{modifier}}-9',
        '10'  => 'row-gap{{modifier}}-10',
    ];

    /**
     * Default row-gap selection per breakpoint.
     *
     * @var array<string, int|string>
     */
    protected array $arrRowGapDefaults = ['xs' => 0];

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

        $this->applyConfiguredContainerPaddingXDefaults();
    }

    /**
     * Read $GLOBALS['responsive']['containerPaddingXDefault'] and overwrite the
     * container-padding-x field defaults without requiring a custom configuration
     * subclass. Mirrors {@see self::applyConfiguredFieldDefaults()}; the side keys
     * are 'main' (content/article/form fields), 'header' and 'footer' (the layout
     * section containers). Each leaf value must be a key of $arrContainerPaddingXClasses
     * (`0`–`10`).
     *
     *   $GLOBALS['responsive']['containerPaddingXDefault'] = 3;
     *       // → main/header/footer defaults = ['xs' => 3]
     *
     *   $GLOBALS['responsive']['containerPaddingXDefault'] = ['header' => 0, 'footer' => 0];
     *       // → header/footer = ['xs' => 0]; main keeps its bundle default
     *
     *   $GLOBALS['responsive']['containerPaddingXDefault'] = [
     *       'main' => ['xs' => 2, 'lg' => 4],
     *   ];
     *       // assigned as-is
     *
     * Pass an empty array for a side (e.g. ['header' => []]) to clear its default
     * back to opt-in (no preselected value).
     *
     * @throws \InvalidArgumentException if any leaf value is not a valid padding key
     */
    private function applyConfiguredContainerPaddingXDefaults(): void
    {
        $config = $GLOBALS['responsive']['containerPaddingXDefault'] ?? null;
        if ($config === null) {
            return;
        }

        $normalized = $this->normalizeDefaultOverride(
            $config,
            ['main', 'header', 'footer'],
            $this->arrContainerPaddingXClasses,
            'containerPaddingXDefault',
        );

        if (isset($normalized['main'])) {
            $this->arrContainerPaddingXDefaults = $normalized['main'];
        }
        if (isset($normalized['header'])) {
            $this->arrContainerPaddingXHeaderDefaults = $normalized['header'];
        }
        if (isset($normalized['footer'])) {
            $this->arrContainerPaddingXFooterDefaults = $normalized['footer'];
        }
    }

    /**
     * Normalize a "default override" config — as accepted by the
     * $GLOBALS['responsive'] hooks above — into a `['<side>' => ['<breakpoint>' => <value>]]`
     * map. Three input shapes are accepted:
     *
     *   - scalar                          → applied to every side at the xs breakpoint
     *   - ['<side>' => scalar]            → that side at the xs breakpoint
     *   - ['<side>' => ['<bp>' => ...]]   → assigned as-is (an empty array clears the side)
     *
     * Every side key is checked against $validSides, every breakpoint against
     * $this->arrBreakpoints and every leaf value against the keys of $validValues.
     * Any unknown side, unknown breakpoint or invalid leaf value throws.
     *
     * @param mixed                    $config      the raw $GLOBALS['responsive'][$globalKey] value
     * @param list<string>             $validSides  accepted side keys
     * @param array<int|string, mixed> $validValues map whose keys enumerate the allowed leaf values
     * @param string                   $globalKey   global array key, used verbatim in error messages
     *
     * @return array<string, array<string, int|string>>
     *
     * @throws \InvalidArgumentException
     */
    private function normalizeDefaultOverride($config, array $validSides, array $validValues, string $globalKey): array
    {
        $label = sprintf('$GLOBALS[\'responsive\'][\'%s\']', $globalKey);

        // Scalar → applies to every side at the xs breakpoint.
        if (is_scalar($config)) {
            $normalized = [];
            foreach ($validSides as $side) {
                $normalized[$side] = ['xs' => $config];
            }
        } elseif (is_array($config)) {
            // Reject unknown side keys (e.g. typos like 'Top', 'heaer').
            $unknownSides = array_diff(array_keys($config), $validSides);
            if ($unknownSides !== []) {
                throw new \InvalidArgumentException(sprintf(
                    '%s has unknown side key(s): %s. Valid sides: %s.',
                    $label,
                    implode(', ', array_map(static fn ($k) => var_export($k, true), $unknownSides)),
                    implode(', ', array_map(static fn ($k) => var_export($k, true), $validSides)),
                ));
            }

            $normalized = [];
            foreach ($validSides as $side) {
                if (!array_key_exists($side, $config)) {
                    continue;
                }
                $value = $config[$side];
                if (is_scalar($value)) {
                    $normalized[$side] = ['xs' => $value];
                } elseif (is_array($value)) {
                    $normalized[$side] = $value;
                } else {
                    throw new \InvalidArgumentException(sprintf(
                        '%s[\'%s\'] must be a scalar or array, got %s.',
                        $label,
                        $side,
                        get_debug_type($value),
                    ));
                }
            }
        } else {
            throw new \InvalidArgumentException(sprintf(
                '%s must be a scalar or array, got %s.',
                $label,
                get_debug_type($config),
            ));
        }

        $validBreakpoints = array_keys($this->arrBreakpoints);

        foreach ($normalized as $side => $breakpoints) {
            foreach ($breakpoints as $breakpoint => $value) {
                if (!in_array($breakpoint, $validBreakpoints, true)) {
                    throw new \InvalidArgumentException(sprintf(
                        '%s[\'%s\'] has unknown breakpoint key %s. Valid breakpoints: %s',
                        $label,
                        $side,
                        var_export($breakpoint, true),
                        implode(', ', array_map(
                            static fn ($k) => var_export($k, true),
                            $validBreakpoints,
                        )),
                    ));
                }

                if ((!is_int($value) && !is_string($value)) || !array_key_exists($value, $validValues)) {
                    throw new \InvalidArgumentException(sprintf(
                        '%s[\'%s\'][\'%s\'] = %s is not a valid value. Valid values: %s',
                        $label,
                        $side,
                        $breakpoint,
                        var_export($value, true),
                        implode(', ', array_map(
                            static fn ($k) => var_export($k, true),
                            array_keys($validValues),
                        )),
                    ));
                }
            }
        }

        return $normalized;
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
            'varRowGapClasses' => $this->arrRowGapClasses,
            'varContainerPaddingXClasses' => $this->arrContainerPaddingXClasses,
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

    public function getRowGapKeys(): array
    {
        return array_keys($this->arrRowGapClasses);
    }

    public function getContainerPaddingXKeys(): array
    {
        return array_keys($this->arrContainerPaddingXClasses);
    }

    public function getDefaults(DataContainer $objDca): void
    {
        parent::getDefaults($objDca);

        $this->applyRowColsDefaults($objDca);
        $this->applyGutterDefaults($objDca);
        $this->applyRowGapDefaults($objDca);
        $this->applyContainerPaddingXDefaults($objDca);
    }

    protected function applyRowColsDefaults(DataContainer $dc): void
    {
        $fields = &$GLOBALS['TL_DCA'][$dc->table]['fields'];
        if (isset($fields['responsiveRowCols'])) {
            $fields['responsiveRowCols']['default'] = $this->arrRowColsDefaults;
        }
    }

    protected function applyGutterDefaults(DataContainer $dc): void
    {
        $fields = &$GLOBALS['TL_DCA'][$dc->table]['fields'];
        $defaults = [
            'responsiveGutter'       => $this->arrGutterDefaults,
            'responsiveGutterHeader' => $this->arrGutterHeaderDefaults,
            'responsiveGutterFooter' => $this->arrGutterFooterDefaults,
        ];
        foreach ($defaults as $field => $default) {
            if (isset($fields[$field])) {
                $fields[$field]['default'] = $default;
            }
        }
    }

    protected function applyRowGapDefaults(DataContainer $dc): void
    {
        $fields = &$GLOBALS['TL_DCA'][$dc->table]['fields'];
        if (isset($fields['responsiveRowGap'])) {
            $fields['responsiveRowGap']['default'] = $this->arrRowGapDefaults;
        }
    }

    protected function applyContainerPaddingXDefaults(DataContainer $dc): void
    {
        $fields = &$GLOBALS['TL_DCA'][$dc->table]['fields'];
        $defaults = [
            'responsiveContainerPaddingX'       => $this->arrContainerPaddingXDefaults,
            'responsiveContainerPaddingXHeader' => $this->arrContainerPaddingXHeaderDefaults,
            'responsiveContainerPaddingXFooter' => $this->arrContainerPaddingXFooterDefaults,
        ];
        foreach ($defaults as $field => $default) {
            if (isset($fields[$field])) {
                $fields[$field]['default'] = $default;
            }
        }
    }
}
