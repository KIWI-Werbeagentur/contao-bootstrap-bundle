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
 * SCSS `kiwi-space()` function). Which steps a field offers is declared per grid
 * subsystem in the `kiwi_bootstrap.grid` config (see GridStyles); this class only
 * maps a step to its key ({@see self::key()}), CSS value ({@see self::value()})
 * and backend label ({@see self::label()}).
 */
final class SpacingScale
{
    /** Base unit; a step of N renders as N × UNIT. */
    public const UNIT = '0.25rem';

    /** Class/key prefix, e.g. the `space` in `space-4`. */
    public const PREFIX = 'space';

    private function __construct()
    {
    }

    /**
     * Option/class key for a step: `4` → `"space-4"`.
     */
    public static function key(int $step): string
    {
        return self::PREFIX . '-' . $step;
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
}
