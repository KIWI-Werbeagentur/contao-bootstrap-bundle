<?php

declare(strict_types=1);

namespace Kiwi\Contao\BootstrapBundle\Configuration;

/**
 * Fixed, value-derived spacing scale (Tailwind-style) — a standalone primitive.
 *
 * The key `space-N` always resolves to `N × UNIT` (0.25rem): the key *is* the
 * value, so it means the same thing everywhere and is never re-defined.
 * `space-4` is `1rem`, `space-16` is `4rem`.
 *
 * The scale itself is unbounded arithmetic — any integer step is valid (see the
 * SCSS `kiwi-space()` function). This class exists for the PHP side, where a
 * finite list IS needed: a backend dropdown can only offer a bounded set of
 * options. That set is the UNION of the steps each wiring asks for via
 * {@see self::requireSteps()}; with no wiring registered it is empty. Build
 * option lists from {@see self::keys()} / {@see self::options()}, labels from
 * {@see self::label()}, values from {@see self::value()}.
 */
final class SpacingScale
{
    /** Base unit; a step of N renders as N × UNIT. */
    public const UNIT = '0.25rem';

    /** Class/key prefix, e.g. the `space` in `space-4`. */
    public const PREFIX = 'space';

    /**
     * Steps asked for by the registered wirings, as a set (step => true). The
     * available scale ({@see self::steps()}) is the union of these.
     *
     * @var array<int, true>
     */
    private static array $required = [];

    private function __construct()
    {
    }

    /**
     * Register the scale steps a wiring needs in its backend dropdown. The
     * available option set is the union of every wiring's steps, so order and
     * duplicate registrations do not matter.
     */
    public static function requireSteps(int ...$steps): void
    {
        foreach ($steps as $step) {
            self::$required[$step] = true;
        }
    }

    /**
     * The available scale steps: the sorted union of all registered steps.
     *
     * @return int[]
     */
    public static function steps(): array
    {
        $steps = array_keys(self::$required);
        sort($steps);

        return $steps;
    }

    /**
     * Option/class key for a step: `4` → `"space-4"`.
     */
    public static function key(int $step): string
    {
        return self::PREFIX . '-' . $step;
    }

    /**
     * All available option/class keys, in scale order.
     *
     * @return list<string>
     */
    public static function keys(): array
    {
        return array_map([self::class, 'key'], self::steps());
    }

    /**
     * Raw CSS length for a step: `4` → `"1rem"`, `0` → `"0rem"`.
     */
    public static function value(int $step): string
    {
        if (!preg_match('/^([\d.]+)\s*([a-z%]*)$/i', self::UNIT, $match)) {
            return $step . ' * ' . self::UNIT;
        }

        $value = $step * (float) $match[1];
        $formatted = rtrim(rtrim(number_format($value, 4, '.', ''), '0'), '.');

        return $formatted . $match[2];
    }

    /**
     * Human-readable label for a step, e.g. `"1rem [space-4]"`. Pass a decimal
     * separator (e.g. `','`) for locale-specific labels.
     */
    public static function label(int $step, string $decimalSeparator = '.'): string
    {
        $value = str_replace('.', $decimalSeparator, self::value($step));

        return $value . ' [' . self::key($step) . ']';
    }

    /**
     * The available scale as `key => CSS value`, e.g.
     * `['space-0' => '0rem', 'space-4' => '1rem', …]`.
     *
     * @return array<string, string>
     */
    public static function map(): array
    {
        $map = [];
        foreach (self::steps() as $step) {
            $map[self::key($step)] = self::value($step);
        }

        return $map;
    }

    /**
     * The available scale as `key => label`, ready to drop into a DCA reference /
     * options array.
     *
     * @return array<string, string>
     */
    public static function options(string $decimalSeparator = '.'): array
    {
        $options = [];
        foreach (self::steps() as $step) {
            $options[self::key($step)] = self::label($step, $decimalSeparator);
        }

        return $options;
    }
}
