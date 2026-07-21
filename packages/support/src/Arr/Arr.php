<?php

declare(strict_types=1);

namespace OpenMeta\Support\Arr;

/**
 * Array access and transforms. Prefer {@see \OpenMeta\Support\Collections\Collection} for fluent pipelines.
 */
final class Arr
{
    /**
     * @param array<array-key, mixed> $array
     */
    public static function get(array $array, string|int $key, mixed $default = null): mixed
    {
        if (is_string($key) && $key === '') {
            return $array;
        }

        if (is_int($key) || ! str_contains($key, '.')) {
            return array_key_exists($key, $array) ? $array[$key] : $default;
        }

        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        $cursor = $array;

        foreach (explode('.', $key) as $segment) {
            if (! is_array($cursor) || ! array_key_exists($segment, $cursor)) {
                return $default;
            }

            $cursor = $cursor[$segment];
        }

        return $cursor;
    }

    /**
     * @param array<array-key, mixed> $array
     * @return array<array-key, mixed>
     */
    public static function set(array &$array, string $key, mixed $value): array
    {
        if ($key === '') {
            $array = is_array($value) ? $value : [$value];

            return $array;
        }

        if (! str_contains($key, '.')) {
            $array[$key] = $value;

            return $array;
        }

        $segments = explode('.', $key);
        $cursor = &$array;

        foreach ($segments as $i => $segment) {
            if ($i === count($segments) - 1) {
                $cursor[$segment] = $value;
                break;
            }

            if (! isset($cursor[$segment]) || ! is_array($cursor[$segment])) {
                $cursor[$segment] = [];
            }

            $cursor = &$cursor[$segment];
        }

        return $array;
    }

    /**
     * @param array<array-key, mixed> $array
     */
    public static function has(array $array, string|int $key): bool
    {
        if (is_int($key) || ! str_contains($key, '.')) {
            return array_key_exists($key, $array);
        }

        $cursor = $array;

        foreach (explode('.', $key) as $segment) {
            if (! is_array($cursor) || ! array_key_exists($segment, $cursor)) {
                return false;
            }

            $cursor = $cursor[$segment];
        }

        return true;
    }

    /**
     * @param array<array-key, mixed> $array
     * @param list<array-key> $keys
     * @return array<array-key, mixed>
     */
    public static function only(array $array, array $keys): array
    {
        $result = [];

        foreach ($keys as $key) {
            if (array_key_exists($key, $array)) {
                $result[$key] = $array[$key];
            }
        }

        return $result;
    }

    /**
     * @param array<array-key, mixed> $array
     * @param list<array-key> $keys
     * @return array<array-key, mixed>
     */
    public static function except(array $array, array $keys): array
    {
        foreach ($keys as $key) {
            unset($array[$key]);
        }

        return $array;
    }

    /**
     * @param array<array-key, mixed> $array
     * @return list<mixed>
     */
    public static function pluck(array $array, string|int $value, string|int|null $key = null): array
    {
        $results = [];

        foreach ($array as $item) {
            if (! is_array($item)) {
                continue;
            }

            $itemValue = self::get($item, (string) $value);

            if ($key === null) {
                $results[] = $itemValue;
                continue;
            }

            $itemKey = self::get($item, (string) $key);
            $results[is_string($itemKey) || is_int($itemKey) ? $itemKey : (string) $itemKey] = $itemValue;
        }

        return $results;
    }

    /**
     * @return array<array-key, mixed>
     */
    public static function wrap(mixed $value): array
    {
        if (is_null($value)) {
            return [];
        }

        return is_array($value) ? $value : [$value];
    }

    /**
     * @param array<array-key, mixed> $array
     * @return list<mixed>
     */
    public static function flatten(array $array, int $depth = PHP_INT_MAX): array
    {
        $result = [];

        foreach ($array as $item) {
            if (! is_array($item)) {
                $result[] = $item;
                continue;
            }

            $values = $depth === 1 ? array_values($item) : self::flatten($item, $depth - 1);

            foreach ($values as $value) {
                $result[] = $value;
            }
        }

        return $result;
    }

    /**
     * @param array<array-key, mixed> $array
     */
    public static function first(array $array, mixed $default = null): mixed
    {
        foreach ($array as $value) {
            return $value;
        }

        return $default;
    }

    /**
     * @param array<array-key, mixed> $array
     */
    public static function last(array $array, mixed $default = null): mixed
    {
        if ($array === []) {
            return $default;
        }

        return $array[array_key_last($array)];
    }

    /**
     * @param array<array-key, mixed> $array
     */
    public static function isAssoc(array $array): bool
    {
        if ($array === []) {
            return false;
        }

        return array_keys($array) !== range(0, count($array) - 1);
    }
}
