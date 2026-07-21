<?php

declare(strict_types=1);

namespace OpenMeta\Security\Sanitization;

/**
 * Inbound sanitization helpers (pure PHP — no WordPress APIs).
 *
 * Static helpers remain the public convenience API; inject {@see DefaultSanitizer} for DI.
 */
final class Sanitizer
{
    public static function text(mixed $value): string
    {
        $value = self::string($value);
        $value = strip_tags($value);
        $value = preg_replace('/[\r\n\t]+/', ' ', $value) ?? $value;

        return trim($value);
    }

    public static function textarea(mixed $value): string
    {
        return trim(strip_tags(self::string($value)));
    }

    public static function email(mixed $value): string
    {
        $value = self::string($value);
        $filtered = filter_var($value, FILTER_SANITIZE_EMAIL);

        return is_string($filtered) ? $filtered : '';
    }

    public static function int(mixed $value, int $default = 0): int
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        return $default;
    }

    public static function float(mixed $value, float $default = 0.0): float
    {
        if (is_float($value) || is_int($value)) {
            return (float) $value;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        return $default;
    }

    public static function bool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        $filtered = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        return $filtered ?? false;
    }

    public static function url(mixed $value): string
    {
        $value = self::string($value);
        $filtered = filter_var($value, FILTER_SANITIZE_URL);

        return is_string($filtered) ? $filtered : '';
    }

    public static function key(mixed $value): string
    {
        $value = strtolower(self::string($value));
        $value = preg_replace('/\s+/', '_', $value) ?? '';
        $value = preg_replace('/[^a-z0-9_\-]/', '', $value) ?? '';

        return $value;
    }

    /**
     * @param array<array-key, mixed> $value
     * @return array<array-key, mixed>
     */
    public static function array(array $value, callable $itemSanitizer): array
    {
        $result = [];

        foreach ($value as $key => $item) {
            $safeKey = is_string($key) ? self::key($key) : $key;
            $result[$safeKey] = $itemSanitizer($item);
        }

        return $result;
    }

    /**
     * Recursively sanitize nested arrays/objects (scalars via text()).
     *
     * @param array<array-key, mixed>|object $value
     * @return array<array-key, mixed>
     */
    public static function nested(array|object $value): array
    {
        if (is_object($value)) {
            $value = get_object_vars($value);
        }

        $result = [];

        foreach ($value as $key => $item) {
            $safeKey = is_string($key) ? self::key($key) : $key;

            if (is_array($item) || is_object($item)) {
                $result[$safeKey] = self::nested($item);
            } else {
                $result[$safeKey] = self::text($item);
            }
        }

        return $result;
    }

    private static function string(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return '';
    }
}
