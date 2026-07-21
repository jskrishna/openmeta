<?php

declare(strict_types=1);

namespace OpenMeta\Support\Helpers;

use Closure;

/**
 * Small cross-cutting helpers that compose other Support modules.
 *
 * Listed helpers: value, tap, with, blank, filled, classBasename.
 */
final class Helpers
{
    /**
     * Return the default value of the given value (resolve Closure).
     */
    public static function value(mixed $value, mixed ...$args): mixed
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }

    /**
     * @template T of mixed
     * @param T $value
     * @param callable(T): void $callback
     * @return T
     */
    public static function tap(mixed $value, callable $callback): mixed
    {
        $callback($value);

        return $value;
    }

    /**
     * @template T of mixed
     * @template TReturn of mixed
     * @param T $value
     * @param callable(T): TReturn $callback
     * @return TReturn
     */
    public static function with(mixed $value, callable $callback): mixed
    {
        return $callback($value);
    }

    public static function blank(mixed $value): bool
    {
        if ($value === null) {
            return true;
        }

        if (is_string($value)) {
            return trim($value) === '';
        }

        if (is_countable($value)) {
            return count($value) === 0;
        }

        return false;
    }

    public static function filled(mixed $value): bool
    {
        return ! self::blank($value);
    }

    public static function classBasename(object|string $class): string
    {
        $class = is_object($class) ? $class::class : $class;
        $position = strrpos($class, '\\');

        return $position === false ? $class : substr($class, $position + 1);
    }
}
