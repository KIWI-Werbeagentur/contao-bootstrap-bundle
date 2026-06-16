<?php

namespace Kiwi\Contao\BootstrapBundle\Configuration;

use Contao\System;
use Kiwi\Contao\BootstrapBundle\Configuration\Grid\GridStyles;
use Kiwi\Contao\BootstrapBundle\Configuration\Grid\SubsystemRegistry;
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
     * Full enumeration of container-padding-x tokens to their class templates.
     * Maps to the .cx-{N} utilities emitted by grid-overrides.scss, which apply
     * the container's own outer L/R padding (opt-in, fluid-gated, non-inheriting)
     * independently from the inter-column gutter.
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

        // Re-key the vertical-spacing scale to config-driven space-N (+ dynamic
        // `default`) before the deprecation mode runs, so the mode still gates only
        // the legacy named buckets on top of the new scale.
        $this->applyVerticalSpacingScale();

        $this->applyDeprecatedSpacingsMode();

        $this->retainLegacyDefaultsForBcMode();

        $this->applyContainerPaddingXScale();

        $this->applyGutterScale();

        $this->applyRowGapScale();
    }

    /**
     * Wire the vertical content spacing to the value-derived space scale. Replaces
     * the legacy numeric spacer scale ('0'…'10', drawn from Bootstrap's $spacers) in
     * $arrSpacings with the configured space-N options + the dynamic `default`,
     * keeping the deprecated named buckets and the noop sentinel intact (still gated
     * by {@see self::applyDeprecatedSpacingsMode()}). The class templates stay
     * direction-aware (p{{direction}}{{modifier}}-…); the matching --spacing-* rules
     * are generated into _spacings.scss. The subsystem registers four partials
     * (articleTop / articleBottom / groupTop / groupBottom), so the dynamic `default`
     * resolves per field to var(--kiwi-vertical-spacing-default-<partial>). All four
     * spacing fields default to `default`, following the configured per-partial values.
     */
    private function applyVerticalSpacingScale(): void
    {
        $grid = $this->gridConfig();
        if (empty($grid['vertical-spacing'])) {
            return;
        }

        $styles = new GridStyles(['vertical-spacing' => $grid['vertical-spacing']], $this->getBreakpointMinWidths());

        // Preserve the noop sentinel and the deprecated named buckets; drop the
        // legacy numeric spacer keys ('0'…'10') and the old `default` bucket (rebuilt
        // below as the dynamic option).
        $preserved = [];
        foreach ($this->arrSpacings as $key => $template) {
            if (preg_match('/^\d+$/', (string) $key) || $key === GridStyles::GENERIC_DEFAULT) {
                continue;
            }
            $preserved[$key] = $template;
        }

        // Config-driven scale (space-N) + the dynamic `default`, direction-aware.
        // The four registered partials (articleTop / articleBottom / groupTop /
        // groupBottom) make the `default` option's class partial-aware, so the
        // runtime class chain can reach the per-partial CSS variables emitted by
        // GridStyles::baseVariables() (--kiwi-vertical-spacing-default-<partial>).
        // The responsive-base engine fills {{partial}} from the options array
        // passed to getResponsiveClasses — see BootstrapFrontendService overrides.
        $hasPartials = SubsystemRegistry::partials('vertical-spacing') !== [];
        $scale = [];
        foreach ($styles->optionKeys('vertical-spacing') as $key) {
            if ($key === GridStyles::GENERIC_DEFAULT && $hasPartials) {
                $scale[$key] = 'p{{direction}}{{modifier}}-default-{{partial}}';
            } else {
                $scale[$key] = 'p{{direction}}{{modifier}}-' . $key;
            }
        }

        $this->arrSpacings = $scale + $preserved;

        // Every vertical-spacing DCA field defaults to the dynamic `default` option,
        // the same as gutter/row-gap/container-padding-x. Its value is resolved per
        // field from the matching partial (articleTop / articleBottom / groupTop /
        // groupBottom) via --kiwi-vertical-spacing-default-<partial>, so changing
        // kiwi_bootstrap.grid.vertical-spacing.defaults propagates to every field left
        // at its default. The partial is supplied at render time (getSpacingClasses /
        // getSpacingTop/Bottom for articles, getGroupSpacingClasses for element groups;
        // see BootstrapFrontendService).
        $this->arrSpacingsDefaults                  = ['xs' => GridStyles::GENERIC_DEFAULT];
        $this->arrSpacingTopDefaults                = ['xs' => GridStyles::GENERIC_DEFAULT];
        $this->arrSpacingBottomDefaults             = ['xs' => GridStyles::GENERIC_DEFAULT];
        $this->arrElementGroupSpacingTopDefaults    = ['xs' => GridStyles::GENERIC_DEFAULT];
        $this->arrElementGroupSpacingBottomDefaults = ['xs' => GridStyles::GENERIC_DEFAULT];
    }

    /**
     * Wire the row-gap subsystem to the value-derived space scale: build its
     * option/class map from `kiwi_bootstrap.grid.row-gap`. The dropdown offers the
     * configured steps plus the dynamic `default` option (→ var(--kiwi-row-gap-default));
     * row-gap has no partials. The field preselects `default` via its DCA definition.
     * Labels live in the responsive language files.
     */
    private function applyRowGapScale(): void
    {
        $grid = $this->gridConfig();
        if (empty($grid['row-gap'])) {
            return;
        }

        $styles = new GridStyles(['row-gap' => $grid['row-gap']], $this->getBreakpointMinWidths());

        $this->arrRowGapClasses = $styles->classMap('row-gap');
    }

    /**
     * Wire the container-padding-x subsystem (.cx-*) to the value-derived space
     * scale: build its option/class map from `kiwi_bootstrap.grid.container-padding-x`.
     * The dropdown offers the configured space-N steps plus the dynamic `default`
     * option, which resolves to var(--kiwi-container-padding-x-default-<partial>) — the
     * partial (main/header/footer) is filled per section in getContainerPaddingXClasses().
     * The fields preselect `default` via their DCA definitions. The fluid-gated .cx-*
     * classes are generated into _grid.scss. Labels live in the language files.
     */
    private function applyContainerPaddingXScale(): void
    {
        $grid = $this->gridConfig();
        if (empty($grid['container-padding-x'])) {
            return;
        }

        $styles = new GridStyles(['container-padding-x' => $grid['container-padding-x']], $this->getBreakpointMinWidths());

        $this->arrContainerPaddingXClasses = $styles->classMap('container-padding-x');
    }

    /**
     * Wire the gutter subsystem to the value-derived space scale: build the
     * option/class map from the `kiwi_bootstrap.grid.gutter` configuration (shipped
     * by the bundle, overridable per project). The dropdown then offers the configured
     * space-N steps, the dynamic `default` option (→ var(--kiwi-gutter-default-<partial>)),
     * and one option per configured variable; the matching classes are generated
     * into _grid.scss. The fields preselect `default` via their DCA definitions.
     * Labels live in the responsive language files.
     */
    private function applyGutterScale(): void
    {
        $grid = $this->gridConfig();
        if (empty($grid['gutter'])) {
            return;
        }

        $styles = new GridStyles(['gutter' => $grid['gutter']], $this->getBreakpointMinWidths());

        $this->arrGutterClasses = $styles->classMap('gutter');
    }

    /**
     * Gutter dropdown option labels (key => label) built from the configured
     * gutter scale, for the responsive language files. Steps use the SpacingScale
     * label with the given decimal separator; `default` and variables get a
     * descriptive label. Returns [] when no gutter config is available.
     *
     * Pass a partial name (main/header/footer) to scope the `default` option's
     * value suffix to that one partial — used by the per-partial DCA fields.
     *
     * @return array<string, string>
     */
    public static function gutterOptionLabels(?string $partial = null, string $decimalSeparator = '.'): array
    {
        return self::subsystemOptionLabels('gutter', $decimalSeparator, $partial);
    }

    /**
     * The processed `kiwi_bootstrap.grid` configuration, or [] when unavailable
     * (e.g. CLI without a booted container).
     *
     * @return array<string, mixed>
     */
    private function gridConfig(): array
    {
        try {
            $container = System::getContainer();
            if ($container !== null && $container->hasParameter('kiwi_bootstrap.grid')) {
                $config = $container->getParameter('kiwi_bootstrap.grid');

                return \is_array($config) ? $config : [];
            }
        } catch (\Throwable) {
            // Fall through.
        }

        return [];
    }

    /**
     * The named spacings (`none`, `gap`, `gap-half`, `xxs` … `xxl`) are deprecated.
     * Which keys actually appear in `$arrSpacings` is controlled by the env var
     * `KIWI_BOOTSTRAP_DEPRECATED_SPACINGS` (the config-driven space-N scale and the
     * dynamic `default` are always kept, on top of which the modes gate the buckets):
     *
     *   unset / 0  →  only the space-N scale + `default` + the `noop` sentinel
     *   1          →  only the deprecated buckets (+ `default` + `noop`)
     *   2          →  both sets (legacy behavior)
     *   3          →  only the space-N scale + `default` (explicit opt-in — same dropdown as the
     *                 implicit default; signals intent to the migration so it won't auto-write a
     *                 fallback even when stored content still uses deprecated values)
     */
    private function applyDeprecatedSpacingsMode(): void
    {
        $mode = $this->getDeprecatedSpacingsMode();

        // Mode 2: keep both sets.
        if ($mode === 2) {
            return;
        }

        // Mode 1: keep only the deprecated buckets, plus the dynamic `default` and
        // the noop sentinel (both part of every mode).
        if ($mode === 1) {
            $keep = array_flip(array_merge(
                self::DEPRECATED_SPACING_KEYS,
                [self::SPACING_NO_OP, GridStyles::GENERIC_DEFAULT],
            ));
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
     * declared defaults), the user-chosen value is left alone.
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


    /**
     * Named-bucket spacing keys deprecated in favour of the value-derived space-N
     * scale. `default` is intentionally NOT listed: it is the permanent dynamic
     * default option (→ var(--kiwi-vertical-spacing-default)), kept in every mode.
     * Listed here so {@see self::applyDeprecatedSpacingsMode()}, the
     * {@see \Kiwi\Contao\BootstrapBundle\Migration\PreserveLegacySpacingsMode}
     * migration, and any future hard-removal release share a single source
     * of truth.
     */
    public const DEPRECATED_SPACING_KEYS = [
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
            'varContainerPaddingXClasses' => $this->arrContainerPaddingXClasses,
            default => parent::__get($name),
        };
    }

    public function getRowCols(): array
    {
        return array_keys($this->arrRowCols);
    }

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

    /**
     * Breakpoint id => min width in px (xs => 0). Used by the grid CSS-variable
     * generation to emit responsive defaults as min-width media queries.
     *
     * @return array<string, int>
     */
    public function getBreakpointMinWidths(): array
    {
        $widths = [];
        foreach ($this->arrBreakpoints as $key => $definition) {
            $widths[$key] = (int) ($definition['breakpoint'] ?? 0);
        }

        return $widths;
    }

    /**
     * Dropdown option labels (key => label) for a grid subsystem, built from its
     * configured scale, for the responsive language files. Steps use the
     * SpacingScale label with the given decimal separator; `default` and variables
     * get a descriptive label. Returns [] when the subsystem isn't configured.
     *
     * Pass a partial name to scope the `default` option's value suffix to that
     * one partial — used by the per-partial DCA fields (header/footer gutter,
     * header/footer container-padding-x) so each shows only the value relevant
     * to it.
     *
     * @return array<string, string>
     */
    public static function subsystemOptionLabels(string $subsystem, string $decimalSeparator = '.', ?string $partial = null): array
    {
        try {
            $container = \Contao\System::getContainer();
            $grid = ($container !== null && $container->hasParameter('kiwi_bootstrap.grid'))
                ? $container->getParameter('kiwi_bootstrap.grid')
                : [];
        } catch (\Throwable) {
            $grid = [];
        }

        if (!\is_array($grid) || empty($grid[$subsystem])) {
            return [];
        }

        $breakpoints = [];
        foreach ((new self())->arrBreakpoints as $key => $definition) {
            $breakpoints[(string) $key] = (int) ($definition['breakpoint'] ?? 0);
        }

        return (new \Kiwi\Contao\BootstrapBundle\Configuration\Grid\GridStyles([$subsystem => $grid[$subsystem]], $breakpoints))
            ->optionLabels($subsystem, $decimalSeparator, $partial);
    }

    /**
     * Whether the configured default of a subsystem partial is `noop` — i.e. the
     * `default` option should render no class for that partial. Used by the wiring
     * (e.g. getSpacingClasses) to exclude `default` for noop partials, so a field
     * left on its default produces no spacing at all. Returns false when unavailable.
     */
    public static function defaultIsNoOp(string $subsystem, string $partial = GridStyles::GENERIC_DEFAULT): bool
    {
        try {
            $container = \Contao\System::getContainer();
            $grid = ($container !== null && $container->hasParameter('kiwi_bootstrap.grid'))
                ? $container->getParameter('kiwi_bootstrap.grid')
                : [];
        } catch (\Throwable) {
            $grid = [];
        }

        if (!\is_array($grid) || empty($grid[$subsystem])) {
            return false;
        }

        return (new GridStyles([$subsystem => $grid[$subsystem]], []))->defaultIsNoOp($subsystem, $partial);
    }
}
