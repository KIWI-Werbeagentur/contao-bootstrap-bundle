<?php

declare(strict_types=1);

namespace Kiwi\Contao\BootstrapBundle\Configuration\Grid;

use Kiwi\Contao\BootstrapBundle\Configuration\SpacingScale;

/**
 * Processes the `kiwi_bootstrap.grid` configuration: validates and resolves each
 * subsystem's options/defaults/variables against {@see SpacingScale} and the
 * {@see SubsystemRegistry}, builds the CSS-variable sets emitted to
 * files/themes/_grid.scss, registers the required scale steps, and exposes the
 * resolver helpers the per-subsystem wiring will consume.
 *
 * Naming of the emitted custom properties:
 *   semantic variable `foo`        → --kiwi-<subsystem>-foo
 *   generic default                → --kiwi-<subsystem>-default
 *   partial default `header`       → --kiwi-<subsystem>-default-header
 *
 * Defaults may be responsive (a `{xs: …, <bp>: …}` map, xs required); only the
 * configured breakpoints are emitted, the rest inherit through the cascade.
 * Semantic variables are single-valued. The literal option key `default` is not
 * a concrete value and is rejected inside defaults/variables; as a selectable
 * backend option it resolves to {@see self::defaultVar()}.
 */
final class GridStyles
{
    /** The `defaults` key that holds the subsystem-wide default; all other keys are partials. */
    public const GENERIC_DEFAULT = 'default';

    /**
     * A `defaults` value meaning "no output": the `default` option, for that partial,
     * emits no class at all (matching the responsive engine's SPACING_NO_OP sentinel).
     * Unlike a scale step or special option it has no CSS value, so it emits no
     * --kiwi-<subsystem>-default-<partial> variable and no utility class; the wiring
     * suppresses the class at render. Only meaningful for subsystems whose rendering
     * can omit the class (the vertical content spacing's [data-spacing-*] mechanism).
     */
    public const NO_OP = 'noop';

    /**
     * @param array<string, array{options?: list<string>, defaults?: array<string, mixed>, variables?: array<string, string>}> $grid
     * @param array<string, int> $breakpoints breakpoint id => min width in px (xs => 0)
     */
    /**
     * Memoized {@see self::build()} result (the object is immutable).
     *
     * @var array{base: list<array{name: string, value: string}>, media: list<array{minWidth: int, variables: list<array{name: string, value: string}>}>}|null
     */
    private ?array $built = null;

    public function __construct(
        private readonly array $grid,
        private readonly array $breakpoints,
    ) {}

    /**
     * CSS variable reference for a subsystem default (optionally a partial), e.g.
     * `defaultVar('gutter', 'header')` → `var(--kiwi-gutter-default-header)`. Pure
     * string building — this is what the `default` backend option resolves to.
     */
    public static function defaultVar(string $subsystem, ?string $partial = null): string
    {
        return 'var(' . self::defaultVarName($subsystem, $partial) . ')';
    }

    /**
     * Register the scale steps the grid config needs (the `space-N` options of
     * every subsystem) with {@see SpacingScale}, so the dropdown union is populated.
     */
    public function registerRequiredSteps(): void
    {
        $steps = [];
        foreach ($this->grid as $config) {
            foreach ($config['options'] ?? [] as $option) {
                if (preg_match('/^space-(\d+)$/', (string) $option, $m)) {
                    $steps[] = (int) $m[1];
                }
            }
        }

        if ($steps !== []) {
            SpacingScale::requireSteps(...$steps);
        }
    }

    /**
     * The CSS variables to emit at the base (xs) level: ordered list of
     * `['name' => '--kiwi-…', 'value' => '1rem']`. Covers every semantic variable
     * and the xs value of every default.
     *
     * @return list<array{name: string, value: string}>
     */
    public function baseVariables(): array
    {
        return $this->build()['base'];
    }

    /**
     * The CSS variables to emit under min-width media queries, grouped and sorted
     * by breakpoint — one entry per non-xs breakpoint a responsive default defines.
     *
     * @return list<array{minWidth: int, variables: list<array{name: string, value: string}>}>
     */
    public function mediaVariableGroups(): array
    {
        return $this->build()['media'];
    }

    /**
     * Resolved default values per explicitly-configured breakpoint, for building
     * truthful backend labels (omitted breakpoints inherit and are left to the
     * widget). E.g. `['xs' => '0.5rem', 'lg' => '1rem']`.
     *
     * @return array<string, string>
     */
    public function defaultValues(string $subsystem, string $partial = self::GENERIC_DEFAULT): array
    {
        $raw = $this->grid[$subsystem]['defaults'][$partial] ?? null;
        if ($raw === null) {
            return [];
        }

        $values = [];
        foreach ($this->normalizeResponsive($raw, $subsystem, "defaults.$partial") as $breakpoint => $option) {
            // A `noop` default has no CSS value; label it as "no spacing".
            $values[$breakpoint] = $option === self::NO_OP
                ? 'none'
                : $this->resolveOption($subsystem, $option, "defaults.$partial.$breakpoint");
        }

        return $values;
    }

    /**
     * Whether a subsystem's configured default for the given partial is `noop`
     * (i.e. the `default` option should emit no class for that partial). Reads only
     * the xs/scalar value — a responsive default is value-based, not noop.
     */
    public function defaultIsNoOp(string $subsystem, string $partial = self::GENERIC_DEFAULT): bool
    {
        $raw = $this->grid[$subsystem]['defaults'][$partial] ?? null;

        if (is_scalar($raw)) {
            return (string) $raw === self::NO_OP;
        }

        return \is_array($raw) && ($raw['xs'] ?? null) === self::NO_OP;
    }

    /**
     * Validate and resolve a single concrete option key to its CSS value.
     * `space-N` resolves via the scale; other keys must be declared by the
     * subsystem's wiring; `default` and unknown keys throw.
     *
     * @throws \InvalidArgumentException
     */
    public function resolveOption(string $subsystem, string $option, string $context): string
    {
        if ($option === self::GENERIC_DEFAULT) {
            throw new \InvalidArgumentException(sprintf(
                'grid.%s.%s: "default" is a selectable backend option, not a concrete value; use a space-N step or a declared option.',
                $subsystem,
                $context,
            ));
        }

        if (preg_match('/^space-(\d+)$/', $option, $m)) {
            return SpacingScale::value((int) $m[1]);
        }

        if (SubsystemRegistry::hasSpecialOption($subsystem, $option)) {
            return SubsystemRegistry::resolveSpecialOption($subsystem, $option);
        }

        throw new \InvalidArgumentException(sprintf(
            'grid.%s.%s references unknown option "%s". Allowed: space-N, or a special option declared by the "%s" subsystem (%s).',
            $subsystem,
            $context,
            $option,
            $subsystem,
            implode(', ', SubsystemRegistry::specialOptionKeys($subsystem)) ?: 'none registered',
        ));
    }

    private static function defaultVarName(string $subsystem, ?string $partial): string
    {
        $suffix = ($partial === null || $partial === self::GENERIC_DEFAULT) ? 'default' : 'default-' . $partial;

        return '--kiwi-' . $subsystem . '-' . $suffix;
    }

    // --------------------------------------------------------------------
    // Wiring helpers — consumed by the per-subsystem DCA/service wiring and
    // the utility-class generation.
    // --------------------------------------------------------------------

    /**
     * Backend dropdown option keys for a subsystem, in order: the configured
     * concrete options (space-N / special keys), the built-in `default`, then one
     * key per configured semantic variable.
     *
     * @return list<string>
     */
    public function optionKeys(string $subsystem): array
    {
        $config = $this->grid[$subsystem] ?? [];

        $keys = $config['options'] ?? [];
        $keys[] = self::GENERIC_DEFAULT;
        foreach (array_keys($config['variables'] ?? []) as $name) {
            $keys[] = (string) $name;
        }

        return $keys;
    }

    /**
     * Option key => class template for a subsystem (using its apply prefix):
     *   space-N / special → "<prefix>{{modifier}}-<key>"
     *   default (partialed)   → "<prefix>{{modifier}}-default-{{partial}}"
     *   default (no partials) → "<prefix>{{modifier}}-default"
     *   <variable>        → "<prefix>{{modifier}}-<variable>"
     *
     * The {{modifier}} (breakpoint) and {{partial}} placeholders are filled by
     * the responsive engine (getResponsiveClasses + its $arrOptions). A subsystem
     * with no declared partials uses the generic default class (no {{partial}}).
     *
     * @return array<string, string>
     */
    public function classMap(string $subsystem): array
    {
        $apply = SubsystemRegistry::apply($subsystem);
        $prefix = $apply['prefix'] ?? $subsystem;
        $hasPartials = SubsystemRegistry::partials($subsystem) !== [];

        $map = [];
        foreach ($this->optionKeys($subsystem) as $key) {
            if ($key !== self::GENERIC_DEFAULT) {
                $map[$key] = $prefix . '{{modifier}}-' . $key;
            } else {
                $map[$key] = $hasPartials
                    ? $prefix . '{{modifier}}-default-{{partial}}'
                    : $prefix . '{{modifier}}-default';
            }
        }

        return $map;
    }

    /**
     * Option key => human label for the dropdown. Steps use the SpacingScale
     * label; `default` and variables get a descriptive label.
     *
     * The `default` label embeds the resolved value(s) it currently points at, so a
     * backend editor sees what the option will actually render as: a scalar default
     * becomes `Default - 1.5rem [default]`; a responsive map becomes
     * `Default - xs:1rem lg:1.5rem [default]`. Partial-aware subsystems (gutter,
     * container-padding-x) additionally list each partial's resolved default
     * (e.g. `Default - main:1.5rem, header:0rem, footer:1.5rem [default]`).
     *
     * The bracketed suffix always holds the option key (`[default]`), so the
     * option name and the value are visually separated: human-readable name first,
     * machine-readable key in brackets.
     *
     * Pass a partial name to scope the suffix to that one partial — used by
     * per-partial DCA fields (main/header/footer gutter, etc.) so each one shows
     * only the value relevant to it.
     *
     * @return array<string, string>
     */
    public function optionLabels(string $subsystem, string $decimalSeparator = '.', ?string $partial = null): array
    {
        $labels = [];
        foreach ($this->optionKeys($subsystem) as $key) {
            if ($key === self::GENERIC_DEFAULT) {
                $labels[$key] = 'Default' . $this->defaultLabelSuffix($subsystem, $partial) . ' [' . $key . ']';
            } elseif (preg_match('/^space-(\d+)$/', $key, $m)) {
                $labels[$key] = SpacingScale::label((int) $m[1], $decimalSeparator);
            } else {
                $labels[$key] = $key . ' [' . $key . ']';
            }
        }

        return $labels;
    }

    /**
     * Build the `- …` segment appended after the human-readable "Default" prefix
     * and before the bracketed option key: a comma-separated list of the
     * configured default's resolved value(s), breakpoint-by-breakpoint, optionally
     * prefixed per partial.
     *
     * When $partial is null, emits a segment for every registered partial that
     * has a configured default (plus the generic default for partial-less
     * subsystems). When $partial is a partial name, restricts the output to that
     * one partial — used by the per-partial DCA fields (e.g. the header/footer
     * gutter fields) so each one shows only the value relevant to it.
     *
     * Returns an empty string when the subsystem has no configured defaults.
     */
    private function defaultLabelSuffix(string $subsystem, ?string $partial = null): string
    {
        $partials = SubsystemRegistry::partials($subsystem);

        if ($partial !== null) {
            $targets = [$partial];
        } elseif ($partials === []) {
            $targets = [null];
        } else {
            $targets = $partials;
        }

        $segments = [];
        foreach ($targets as $target) {
            $values = $this->defaultValues($subsystem, $target ?? self::GENERIC_DEFAULT);
            if ($values === []) {
                continue;
            }
            // A single-value default (the common case) is rendered as its raw CSS
            // value: "1.5rem". A responsive map keeps the breakpoint prefixes so
            // the editor can tell which viewport each value applies to:
            // "xs:1rem lg:1.5rem".
            $rendered = count($values) === 1
                ? implode('', $values)
                : implode(' ', array_map(
                    static fn (string $bp, string $value): string => $bp . ':' . $value,
                    array_keys($values),
                    array_values($values),
                ));
            // In per-partial mode the field *is* the partial (its label names it),
            // so a "header:" / "footer:" / "main:" prefix is redundant — the value
            // alone is unambiguous. In the "all partials" mode (no $partial passed)
            // we keep the prefix so the editor sees at a glance which section each
            // value applies to.
            $segments[] = $target === null || $partial !== null
                ? $rendered
                : $target . ':' . $rendered;
        }

        return $segments === [] ? '' : ' - ' . implode(', ', $segments);
    }

    /**
     * Responsive utility-class data for every subsystem that declares an apply
     * spec: its prefix/property plus one entry per emittable class —
     *   step `space-N`     → literal value
     *   each partial       → "default-<partial>" → var(--kiwi-<sub>-default-<partial>)
     *   each variable      → "<var>"             → var(--kiwi-<sub>-<var>)
     *
     * @return list<array{prefix: string, property: string, entries: list<array{suffix: string, value: string}>}>
     */
    public function utilityClassSets(): array
    {
        $sets = [];

        foreach ($this->grid as $subsystem => $config) {
            $apply = SubsystemRegistry::apply($subsystem);
            // A prefix-only apply (no `property`) means the subsystem generates its
            // own classes (e.g. the fluid-gated cx); skip it from the flat loop.
            if ($apply === null || !isset($apply['property'])) {
                continue;
            }

            $sets[] = [
                'prefix' => $apply['prefix'],
                'property' => $apply['property'],
                'entries' => $this->utilityEntries($subsystem),
            ];
        }

        return $sets;
    }

    /**
     * The emittable utility-class entries for one subsystem — one `suffix => value`
     * per concrete step, per default (generic, or `default-<partial>` for each
     * declared partial), and per semantic variable. Shared by {@see self::utilityClassSets()}
     * (subsystems applied via the generic flat loop) and by subsystems whose wiring
     * emits its classes itself (e.g. the fluid-gated container-padding-x).
     *
     * @return list<array{suffix: string, value: string}>
     */
    public function utilityEntries(string $subsystem): array
    {
        $config = $this->grid[$subsystem] ?? [];

        $entries = [];

        foreach ($config['options'] ?? [] as $option) {
            $entries[] = [
                'suffix' => (string) $option,
                'value' => $this->resolveOption($subsystem, (string) $option, 'options'),
            ];
        }

        // A `noop` default emits no class (and no var); skip those partials so no
        // class referencing a non-existent --kiwi-…-default-<partial> is generated.
        $partials = SubsystemRegistry::partials($subsystem);
        if ($partials === []) {
            // No partials: a single generic default class.
            if (!$this->defaultIsNoOp($subsystem)) {
                $entries[] = [
                    'suffix' => 'default',
                    'value' => self::defaultVar($subsystem, null),
                ];
            }
        } else {
            foreach ($partials as $partial) {
                if ($this->defaultIsNoOp($subsystem, $partial)) {
                    continue;
                }
                $entries[] = [
                    'suffix' => 'default-' . $partial,
                    'value' => self::defaultVar($subsystem, $partial),
                ];
            }
        }

        foreach (array_keys($config['variables'] ?? []) as $name) {
            $entries[] = [
                'suffix' => (string) $name,
                'value' => 'var(--kiwi-' . $subsystem . '-' . $name . ')',
            ];
        }

        return $entries;
    }

    /**
     * Validate the whole config and build the base + media CSS-variable sets.
     *
     * @return array{base: list<array{name: string, value: string}>, media: list<array{minWidth: int, variables: list<array{name: string, value: string}>}>}
     */
    private function build(): array
    {
        if ($this->built !== null) {
            return $this->built;
        }

        $base = [];
        $media = [];

        foreach ($this->grid as $subsystem => $config) {
            $this->assertKnownSubsystem($subsystem);

            $options = $config['options'] ?? [];
            $this->validateOptions($subsystem, $options);

            // Semantic variables: name => option key (single value, must be a listed option).
            foreach ($config['variables'] ?? [] as $name => $option) {
                if (!is_string($option)) {
                    throw new \InvalidArgumentException(sprintf(
                        'grid.%s.variables.%s must be a single option key — variables are not responsive, got %s.',
                        $subsystem,
                        $name,
                        get_debug_type($option),
                    ));
                }
                $this->assertListed($subsystem, $option, $options, "variables.$name");
                $base[] = [
                    'name' => '--kiwi-' . $subsystem . '-' . $name,
                    'value' => $this->resolveOption($subsystem, (string) $option, "variables.$name"),
                ];
            }

            // Defaults: generic + partials, each scalar or responsive map (must be listed options).
            foreach ($config['defaults'] ?? [] as $partial => $raw) {
                $this->assertKnownPartial($subsystem, (string) $partial);
                $varName = self::defaultVarName($subsystem, (string) $partial);
                foreach ($this->normalizeResponsive($raw, $subsystem, "defaults.$partial") as $breakpoint => $option) {
                    // `noop`: the field renders no class for this partial, so there is
                    // no value and no --kiwi-<subsystem>-default-<partial> var to emit.
                    if ($option === self::NO_OP) {
                        continue;
                    }
                    $this->assertListed($subsystem, $option, $options, "defaults.$partial.$breakpoint");
                    $value = $this->resolveOption($subsystem, $option, "defaults.$partial.$breakpoint");

                    $minWidth = $this->breakpoints[$breakpoint];
                    if ($minWidth === 0) {
                        $base[] = ['name' => $varName, 'value' => $value];
                    } else {
                        $media[$minWidth][] = ['name' => $varName, 'value' => $value];
                    }
                }
            }
        }

        ksort($media);
        $mediaGroups = [];
        foreach ($media as $minWidth => $variables) {
            $mediaGroups[] = ['minWidth' => $minWidth, 'variables' => $variables];
        }

        return $this->built = ['base' => $base, 'media' => $mediaGroups];
    }

    /**
     * Reject a configured subsystem id that no wiring owns (catches typos like
     * "gutters" vs "gutter", which would otherwise silently emit unused
     * --kiwi-… variables). The registry is the source of truth for valid ids;
     * while it is empty (no wiring registered yet) there is nothing to validate
     * against, so the check is skipped — it activates as soon as any subsystem
     * is registered.
     */
    private function assertKnownSubsystem(string $subsystem): void
    {
        $known = SubsystemRegistry::ids();
        if ($known === [] || \in_array($subsystem, $known, true)) {
            return;
        }

        throw new \InvalidArgumentException(sprintf(
            'grid.%s is not a registered subsystem. Registered: %s.',
            $subsystem,
            implode(', ', $known),
        ));
    }

    /**
     * Reject a `defaults` key that is neither the generic `default` nor a partial
     * the subsystem's wiring declared (catches typos like "headr"). Skipped while
     * the registry is empty (no wiring yet), same as {@see self::assertKnownSubsystem()}.
     */
    private function assertKnownPartial(string $subsystem, string $partial): void
    {
        if ($partial === self::GENERIC_DEFAULT || SubsystemRegistry::ids() === []) {
            return;
        }

        $partials = SubsystemRegistry::partials($subsystem);
        if (\in_array($partial, $partials, true)) {
            return;
        }

        throw new \InvalidArgumentException(sprintf(
            'grid.%s.defaults.%s is not a known partial of the "%s" subsystem. Known partials: %s.',
            $subsystem,
            $partial,
            $subsystem,
            implode(', ', $partials) ?: 'none declared (only "default")',
        ));
    }

    /**
     * @param list<string> $options
     */
    private function validateOptions(string $subsystem, array $options): void
    {
        foreach ($options as $option) {
            if ($option === self::GENERIC_DEFAULT) {
                throw new \InvalidArgumentException(sprintf(
                    'grid.%s.options must not list "default"; it is a built-in option, always available.',
                    $subsystem,
                ));
            }
            // resolveOption throws on anything that is neither space-N nor a declared special key.
            $this->resolveOption($subsystem, (string) $option, 'options');
        }
    }

    /**
     * @param list<string> $options
     */
    private function assertListed(string $subsystem, string $option, array $options, string $context): void
    {
        if ($option === self::GENERIC_DEFAULT) {
            return; // resolveOption() will reject it with a precise message.
        }
        if (!\in_array($option, $options, true)) {
            throw new \InvalidArgumentException(sprintf(
                'grid.%s.%s = "%s" must be one of the subsystem\'s options (%s).',
                $subsystem,
                $context,
                $option,
                implode(', ', $options) ?: 'none configured',
            ));
        }
    }

    /**
     * Normalize a default value to a `breakpoint => option` map. A scalar means
     * `{xs: value}`; a map must include `xs` and use known breakpoint keys.
     *
     * @return array<string, string>
     */
    private function normalizeResponsive(mixed $raw, string $subsystem, string $context): array
    {
        if (is_scalar($raw)) {
            return ['xs' => (string) $raw];
        }

        if (!\is_array($raw)) {
            throw new \InvalidArgumentException(sprintf(
                'grid.%s.%s must be an option key or a {breakpoint: option} map, got %s.',
                $subsystem,
                $context,
                get_debug_type($raw),
            ));
        }

        if (!isset($raw['xs'])) {
            throw new \InvalidArgumentException(sprintf(
                'grid.%s.%s is a responsive map and must define the "xs" breakpoint.',
                $subsystem,
                $context,
            ));
        }

        $normalized = [];
        foreach ($raw as $breakpoint => $option) {
            if (!isset($this->breakpoints[$breakpoint])) {
                throw new \InvalidArgumentException(sprintf(
                    'grid.%s.%s uses unknown breakpoint "%s". Known: %s.',
                    $subsystem,
                    $context,
                    $breakpoint,
                    implode(', ', array_keys($this->breakpoints)),
                ));
            }
            $normalized[(string) $breakpoint] = (string) $option;
        }

        return $normalized;
    }
}
