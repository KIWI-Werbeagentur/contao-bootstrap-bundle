<?php

declare(strict_types=1);

namespace Kiwi\Contao\BootstrapBundle\Configuration\Grid;

/**
 * Registry of grid subsystems. A subsystem is owned by its wiring (yet to come);
 * the wiring registers the subsystem id together with the special option keys it
 * supports beyond the universal `space-N` scale and the built-in `default`.
 *
 * Each special option must carry a value (a CSS string) or a way to derive it
 * (a callable `fn(string $key): string`), so the config layer can resolve any
 * configured option to a concrete CSS value. A subsystem also declares the
 * partial names it supports (e.g. header/footer), beyond the always-available
 * generic `default`, so the config layer can reject misspelled partials.
 *
 *     SubsystemRegistry::register('gutter', ['auto' => 'auto', 'none' => '0'], ['header', 'footer']);
 *
 * This is static, request-scoped state, populated during config bootstrap — the
 * same lifetime model the rest of the bundle's `$GLOBALS['responsive']` config uses.
 */
final class SubsystemRegistry
{
    /** @var array<string, array<string, string|callable>> */
    private static array $subsystems = [];

    /** @var array<string, list<string>> */
    private static array $partials = [];

    /** @var array<string, array{prefix: string, property: string}> */
    private static array $apply = [];

    private function __construct()
    {
    }

    /**
     * Register (or extend) a subsystem with the special option keys and partial
     * names it allows, and optionally how it applies an option as a utility class.
     *
     * The `$apply` descriptor lets the grid generator emit the subsystem's
     * responsive utility classes (.<prefix>{infix}-<key>) setting a single CSS
     * property, e.g. the gutter applies via `gx` → `--bs-gutter-x`. Subsystems
     * with a more complex application (fluid-gated cx, the [data-spacing]
     * mechanism, …) omit it and generate their classes themselves.
     *
     * @param array<string, string|callable>     $specialOptions key => CSS value or fn(string $key): string
     * @param list<string>                        $partials       partial names beyond the generic `default`
     * @param array{prefix: string, property: string}|null $apply  utility-class prefix + target CSS property
     */
    public static function register(string $id, array $specialOptions = [], array $partials = [], ?array $apply = null): void
    {
        self::$subsystems[$id] = array_merge(self::$subsystems[$id] ?? [], $specialOptions);
        self::$partials[$id] = array_values(array_unique(array_merge(self::$partials[$id] ?? [], $partials)));

        if ($apply !== null) {
            self::$apply[$id] = $apply;
        }
    }

    /**
     * The utility-class application descriptor (prefix + property), or null when
     * the subsystem generates its classes itself.
     *
     * @return array{prefix: string, property: string}|null
     */
    public static function apply(string $id): ?array
    {
        return self::$apply[$id] ?? null;
    }

    public static function isRegistered(string $id): bool
    {
        return isset(self::$subsystems[$id]);
    }

    /**
     * @return list<string>
     */
    public static function ids(): array
    {
        return array_keys(self::$subsystems);
    }

    /**
     * The special option keys a subsystem declared.
     *
     * @return list<string>
     */
    public static function specialOptionKeys(string $id): array
    {
        return array_keys(self::$subsystems[$id] ?? []);
    }

    public static function hasSpecialOption(string $id, string $key): bool
    {
        return isset(self::$subsystems[$id][$key]);
    }

    /**
     * The partial names a subsystem declared (beyond the generic `default`).
     *
     * @return list<string>
     */
    public static function partials(string $id): array
    {
        return self::$partials[$id] ?? [];
    }

    /**
     * Resolve a declared special option to its CSS value.
     *
     * @throws \InvalidArgumentException if the key is not a special option of the subsystem
     */
    public static function resolveSpecialOption(string $id, string $key): string
    {
        if (!self::hasSpecialOption($id, $key)) {
            throw new \InvalidArgumentException(sprintf(
                'Option "%s" is not a registered special option of grid subsystem "%s".',
                $key,
                $id,
            ));
        }

        $value = self::$subsystems[$id][$key];

        return \is_callable($value) ? (string) $value($key) : (string) $value;
    }

    /**
     * Drop all registrations (test helper).
     */
    public static function reset(): void
    {
        self::$subsystems = [];
        self::$partials = [];
        self::$apply = [];
    }
}
