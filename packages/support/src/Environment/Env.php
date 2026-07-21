<?php

declare(strict_types=1);

namespace OpenMeta\Support\Environment;

/**
 * Typed environment access. Does not replace Core ConfigRepository.
 *
 * Precedence: $_ENV → $_SERVER → getenv(). Never log secret values.
 */
final class Env
{
    public static function has(string $key): bool
    {
        return self::raw($key) !== null;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = self::raw($key);

        return $value === null ? $default : $value;
    }

    public static function string(string $key, ?string $default = null): ?string
    {
        $value = self::raw($key);

        if ($value === null) {
            return $default;
        }

        return (string) $value;
    }

    public static function bool(string $key, bool $default = false): bool
    {
        $value = self::raw($key);

        if ($value === null) {
            return $default;
        }

        $filtered = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        return $filtered ?? $default;
    }

    public static function int(string $key, int $default = 0): int
    {
        $value = self::raw($key);

        if ($value === null || $value === '') {
            return $default;
        }

        return (int) $value;
    }

    private static function raw(string $key): ?string
    {
        if (array_key_exists($key, $_ENV)) {
            return self::stringify($_ENV[$key]);
        }

        if (array_key_exists($key, $_SERVER)) {
            return self::stringify($_SERVER[$key]);
        }

        $value = getenv($key);

        if ($value === false) {
            return null;
        }

        return self::stringify($value);
    }

    private static function stringify(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return null;
    }
}
