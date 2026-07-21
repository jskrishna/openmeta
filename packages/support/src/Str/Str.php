<?php

declare(strict_types=1);

namespace OpenMeta\Support\Str;

/**
 * Encoding-aware string helpers (multibyte where claimed).
 */
final class Str
{
    public static function length(string $value, ?string $encoding = 'UTF-8'): int
    {
        return mb_strlen($value, $encoding ?? 'UTF-8');
    }

    public static function lower(string $value, ?string $encoding = 'UTF-8'): string
    {
        return mb_strtolower($value, $encoding ?? 'UTF-8');
    }

    public static function upper(string $value, ?string $encoding = 'UTF-8'): string
    {
        return mb_strtoupper($value, $encoding ?? 'UTF-8');
    }

    public static function startsWith(string $haystack, string $needle): bool
    {
        return $needle === '' || str_starts_with($haystack, $needle);
    }

    public static function endsWith(string $haystack, string $needle): bool
    {
        return $needle === '' || str_ends_with($haystack, $needle);
    }

    public static function contains(string $haystack, string $needle): bool
    {
        return $needle === '' || str_contains($haystack, $needle);
    }

    public static function limit(
        string $value,
        int $limit = 100,
        string $end = '...',
        ?string $encoding = 'UTF-8'
    ): string {
        if ($limit < 0) {
            $limit = 0;
        }

        if (mb_strlen($value, $encoding ?? 'UTF-8') <= $limit) {
            return $value;
        }

        return rtrim(mb_substr($value, 0, $limit, $encoding ?? 'UTF-8')) . $end;
    }

    public static function snake(string $value, string $delimiter = '_'): string
    {
        if (! ctype_lower($value)) {
            $value = (string) preg_replace('/\s+/u', '', ucwords($value));
            $value = (string) preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value);
            $value = mb_strtolower($value, 'UTF-8');
        }

        return str_replace(['-', ' '], $delimiter, $value);
    }

    public static function camel(string $value): string
    {
        return lcfirst(self::studly($value));
    }

    public static function studly(string $value): string
    {
        $value = str_replace(['-', '_'], ' ', $value);
        $value = ucwords($value);

        return str_replace(' ', '', $value);
    }

    public static function slug(string $value, string $separator = '-'): string
    {
        $value = mb_strtolower(trim($value), 'UTF-8');
        $value = (string) preg_replace('/[^\p{L}\p{N}]+/u', $separator, $value);
        $value = (string) preg_replace('/' . preg_quote($separator, '/') . '+/', $separator, $value);

        return trim($value, $separator);
    }

    public static function before(string $subject, string $search): string
    {
        if ($search === '') {
            return $subject;
        }

        $pos = mb_strpos($subject, $search, 0, 'UTF-8');

        return $pos === false ? $subject : mb_substr($subject, 0, $pos, 'UTF-8');
    }

    public static function after(string $subject, string $search): string
    {
        if ($search === '') {
            return $subject;
        }

        $pos = mb_strpos($subject, $search, 0, 'UTF-8');

        return $pos === false
            ? $subject
            : mb_substr($subject, $pos + mb_strlen($search, 'UTF-8'), null, 'UTF-8');
    }

    public static function isEmpty(?string $value): bool
    {
        return $value === null || $value === '';
    }
}
