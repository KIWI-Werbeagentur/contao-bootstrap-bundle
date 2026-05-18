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
        // Bootstrap-aligned spacer sizes; emit dedicated ".p[t|b]-spacer-N" classes
        // (see contao/templates/twig/responsive/spacings.scss.twig) to avoid collisions
        // with Bootstrap's own ".p[t|b]-N" utilities, which set padding directly instead
        // of going through the bundle's --spacing-* / [data-spacing-*] mechanism.
        '0'   => 'p{{direction}}{{modifier}}-spacer-0',
        '1'   => 'p{{direction}}{{modifier}}-spacer-1',
        '2'   => 'p{{direction}}{{modifier}}-spacer-2',
        '3'   => 'p{{direction}}{{modifier}}-spacer-3',
        '4'   => 'p{{direction}}{{modifier}}-spacer-4',
        '5'   => 'p{{direction}}{{modifier}}-spacer-5',
        '6'   => 'p{{direction}}{{modifier}}-spacer-6',
        '7'   => 'p{{direction}}{{modifier}}-spacer-7',
        '8'   => 'p{{direction}}{{modifier}}-spacer-8',
        '9'   => 'p{{direction}}{{modifier}}-spacer-9',
        '10'  => 'p{{direction}}{{modifier}}-spacer-10',
        // Named buckets (custom semantic sizes, project-defined values)
        self::SPACING_NO_OP => '',
        'default'  => 'p{{direction}}{{modifier}}-default',
        'none'     => 'p{{direction}}{{modifier}}-none',
        'gap'      => 'p{{direction}}{{modifier}}-gap',
        'gap-half' => 'p{{direction}}{{modifier}}-gap-half',
        'xxs'      => 'p{{direction}}{{modifier}}-xxs',
        'xs'       => 'p{{direction}}{{modifier}}-xs',
        'sm'       => 'p{{direction}}{{modifier}}-sm',
        'md'       => 'p{{direction}}{{modifier}}-md',
        'lg'       => 'p{{direction}}{{modifier}}-lg',
        'xl'       => 'p{{direction}}{{modifier}}-xl',
        'xxl'      => 'p{{direction}}{{modifier}}-xxl',
    ];

    /**
     * @deprecated since 1.x, will be removed in 2.0.
     *             Use {@see self::$arrSpacingTopDefaults} / {@see self::$arrSpacingBottomDefaults}.
     */
    protected array $arrSpacingsDefaults = ['xs' => 7];
    protected array $arrSpacingTopDefaults = ['xs' => 7];
    protected array $arrSpacingBottomDefaults = ['xs' => 7];

    protected array $arrElementGroupSpacingTopDefaults = ['xs' => self::SPACING_NO_OP];
    protected array $arrElementGroupSpacingBottomDefaults = ['xs' => self::SPACING_NO_OP];

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

        $this->applyDeprecatedSpacingsMode();

        $this->retainLegacyDefaultsForBcMode();

        $this->applyConfiguredFieldDefaults();
    }

    /**
     * The named spacings (`default`, `none`, `gap`, `gap-half`, `xxs` … `xxl`)
     * are deprecated. Which keys actually appear
     * in `$arrSpacings` is controlled by the env var `KIWI_BOOTSTRAP_DEPRECATED_SPACINGS`:
     *
     *   unset / 0  →  only the new spacer-based keys (numeric `0`–`10` + the `noop` sentinel)
     *   1          →  only the deprecated keys (+ `noop`)
     *   2          →  both sets (legacy behavior)
     *   3          →  only the new spacer-based keys (explicit opt-in — same dropdown as the implicit
     *                 default; signals intent to the migration so it won't auto-write a fallback even
     *                 when stored content still uses deprecated values)
     */
    private function applyDeprecatedSpacingsMode(): void
    {
        $mode = $this->getDeprecatedSpacingsMode();

        // Mode 2: keep both sets.
        if ($mode === 2) {
            return;
        }

        // Mode 1: keep only the deprecated keys plus the noop sentinel.
        if ($mode === 1) {
            $keep = array_flip(array_merge(self::DEPRECATED_SPACING_KEYS, [self::SPACING_NO_OP]));
            $this->arrSpacings = array_intersect_key($this->arrSpacings, $keep);
            return;
        }

        // Mode 3 and default fallback: keep only the new spacer-based keys.
        foreach (self::DEPRECATED_SPACING_KEYS as $key) {
            unset($this->arrSpacings[$key]);
        }
    }

    /**
     * For installations that still use the deprecated spacing options (env mode 1 or
     * 2) the bundle's field defaults are reverted to `['xs' => 'default']`.
     *
     * If a subclass has redeclared any of the three defaults properties (detected
     * via reflection by comparing the effective declared defaults to this bundle's
     * declared defaults), the user-chosen value is left alone. An explicit
     * `$GLOBALS['responsive']['spacingDefault']` set elsewhere is applied later by
     * {@see self::applyConfiguredFieldDefaults()} and overrides whatever this method
     * decided.
     */
    private function retainLegacyDefaultsForBcMode(): void
    {
        $mode = $this->getDeprecatedSpacingsMode();
        if ($mode !== 1 && $mode !== 2) {
            return;
        }

        $bundleDefaults    = (new \ReflectionClass(self::class))->getDefaultProperties();
        $effectiveDefaults = (new \ReflectionClass(static::class))->getDefaultProperties();

        foreach (['arrSpacingTopDefaults', 'arrSpacingBottomDefaults'] as $prop) {
            // A subclass redeclared the property → respect the project's choice.
            if (($effectiveDefaults[$prop] ?? null) !== ($bundleDefaults[$prop] ?? null)) {
                continue;
            }

            $this->$prop = ['xs' => 'default'];
        }
    }

    /**
     * Read and normalize the `KIWI_BOOTSTRAP_DEPRECATED_SPACINGS` env var. Non-numeric
     * and unrecognised values collapse to mode 0 (the new default behaviour).
     */
    private function getDeprecatedSpacingsMode(): int
    {
        return (int) ($_ENV['KIWI_BOOTSTRAP_DEPRECATED_SPACINGS'] ?? 0);
    }

    /**
     * Read $GLOBALS['responsive']['spacingDefault'] and overwrite the content-spacing
     * top/bottom field defaults without requiring a custom configuration subclass.
     *
     * Accepted shapes (each leaf value must be a key of $arrSpacings):
     *
     *   $GLOBALS['responsive']['spacingDefault'] = 6;
     *       // → arrSpacingTopDefaults    = ['xs' => 6]
     *       //   arrSpacingBottomDefaults = ['xs' => 6]
     *
     *   $GLOBALS['responsive']['spacingDefault'] = ['top' => 6, 'bottom' => 4];
     *       // → arrSpacingTopDefaults    = ['xs' => 6]
     *       //   arrSpacingBottomDefaults = ['xs' => 4]
     *
     *   $GLOBALS['responsive']['spacingDefault'] = [
     *       'top'    => ['xs' => 4, 'lg' => 6],
     *       'bottom' => ['xs' => 6],
     *   ];
     *       // assigned as-is
     *
     * Element-group field defaults are intentionally not covered by this hook — they
     * remain customizable via a {@see BootstrapConfiguration} subclass.
     *
     * @throws \InvalidArgumentException if any leaf value is not a valid spacing key
     */
    private function applyConfiguredFieldDefaults(): void
    {
        $config = $GLOBALS['responsive']['spacingDefault'] ?? null;
        if ($config === null) {
            return;
        }

        // Scalar → applies symmetrically to top and bottom at the xs breakpoint.
        if (is_scalar($config)) {
            $normalized = ['top' => ['xs' => $config], 'bottom' => ['xs' => $config]];
        } elseif (is_array($config)) {
            // Reject unknown side keys (e.g. typos like 'Top', 'header')
            $validSides   = ['top', 'bottom'];
            $unknownSides = array_diff(array_keys($config), $validSides);
            if ($unknownSides !== []) {
                throw new \InvalidArgumentException(sprintf(
                    '$GLOBALS[\'responsive\'][\'spacingDefault\'] has unknown side key(s): %s. Valid sides: %s.',
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
                        '$GLOBALS[\'responsive\'][\'spacingDefault\'][\'%s\'] must be a scalar or array, got %s.',
                        $side,
                        get_debug_type($value),
                    ));
                }
            }
        } else {
            throw new \InvalidArgumentException(sprintf(
                '$GLOBALS[\'responsive\'][\'spacingDefault\'] must be a scalar or array, got %s.',
                get_debug_type($config),
            ));
        }

        $validBreakpoints = array_keys($this->arrBreakpoints);

        foreach ($normalized as $side => $breakpoints) {
            foreach ($breakpoints as $breakpoint => $value) {
                if (!in_array($breakpoint, $validBreakpoints, true)) {
                    throw new \InvalidArgumentException(sprintf(
                        '$GLOBALS[\'responsive\'][\'spacingDefault\'][\'%s\'] has unknown breakpoint key %s. Valid breakpoints: %s',
                        $side,
                        var_export($breakpoint, true),
                        implode(', ', array_map(
                            static fn ($k) => var_export($k, true),
                            $validBreakpoints,
                        )),
                    ));
                }

                if ((!is_int($value) && !is_string($value)) || !array_key_exists($value, $this->arrSpacings)) {
                    throw new \InvalidArgumentException(sprintf(
                        '$GLOBALS[\'responsive\'][\'spacingDefault\'][\'%s\'][\'%s\'] = %s is not a valid spacing key. Valid keys: %s',
                        $side,
                        $breakpoint,
                        var_export($value, true),
                        implode(', ', array_map(
                            static fn ($k) => var_export($k, true),
                            array_keys($this->arrSpacings),
                        )),
                    ));
                }
            }
        }

        if (isset($normalized['top'])) {
            $this->arrSpacingTopDefaults = $normalized['top'];
        }
        if (isset($normalized['bottom'])) {
            $this->arrSpacingBottomDefaults = $normalized['bottom'];
        }
    }

    /**
     * Spacing keys deprecated in favour of the numeric spacer scale (`0`…`10`).
     * Listed here so {@see self::applyDeprecatedSpacingsMode()}, the
     * {@see \Kiwi\Contao\BootstrapBundle\Migration\PreserveLegacySpacingsMode}
     * migration, and any future hard-removal release share a single source
     * of truth.
     */
    public const DEPRECATED_SPACING_KEYS = [
        'default',
        'none',
        'gap',
        'gap-half',
        'xxs',
        'xs',
        'sm',
        'md',
        'lg',
        'xl',
        'xxl',
    ];

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

    public function getDefaults(DataContainer $objDca): void
    {
        parent::getDefaults($objDca);

        $this->applyRowColsDefaults($objDca);
        $this->applyGutterDefaults($objDca);
        $this->applyRowGapDefaults($objDca);
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
        $fields  = &$GLOBALS['TL_DCA'][$dc->table]['fields'];
        $default = serialize($this->arrGutterDefaults);
        foreach (['responsiveGutter', 'responsiveGutterHeader', 'responsiveGutterFooter'] as $field) {
            if (isset($fields[$field])) {
                $fields[$field]['default'] = $default;
            }
        }
    }

    protected function applyRowGapDefaults(DataContainer $dc): void
    {
        $fields = &$GLOBALS['TL_DCA'][$dc->table]['fields'];
        if (isset($fields['responsiveRowGap'])) {
            $fields['responsiveRowGap']['default'] = serialize($this->arrRowGapDefaults);
        }
    }
}
