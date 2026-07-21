<?php

declare(strict_types=1);

namespace OpenMeta\Core\Support;

/**
 * Lightweight array helpers for config and runtime data.
 */
final class Arr
{
    /**
     * @param array<string, mixed> $array
     */
    public static function get(array $array, string $key, mixed $default = null): mixed
    {
        if ($key === '') {
            return $array;
        }

        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        if (! str_contains($key, '.')) {
            return $default;
        }

        $segments = explode('.', $key);
        $cursor = $array;

        foreach ($segments as $segment) {
            if (! is_array($cursor) || ! array_key_exists($segment, $cursor)) {
                return $default;
            }

            $cursor = $cursor[$segment];
        }

        return $cursor;
    }
}
