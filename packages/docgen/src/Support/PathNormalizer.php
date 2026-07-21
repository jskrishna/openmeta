<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Support;

/**
 * Resolves and normalises relative documentation paths (handling `.` / `..`).
 */
final class PathNormalizer
{
    public static function resolve(string $baseDir, string $relative): string
    {
        $combined = $relative !== '' && ($relative[0] === '/' || preg_match('#^[A-Za-z]:#', $relative) === 1)
            ? $relative
            : rtrim($baseDir, '/\\') . '/' . $relative;

        return self::normalize($combined);
    }

    public static function normalize(string $path): string
    {
        $path = str_replace('\\', '/', $path);
        $prefix = str_starts_with($path, '/') ? '/' : '';
        $segments = [];

        foreach (explode('/', $path) as $segment) {
            if ($segment === '' || $segment === '.') {
                continue;
            }

            if ($segment === '..') {
                array_pop($segments);
                continue;
            }

            $segments[] = $segment;
        }

        return $prefix . implode('/', $segments);
    }

    public static function directoryOf(string $path): string
    {
        $path = str_replace('\\', '/', $path);
        $position = strrpos($path, '/');

        return $position === false ? '' : substr($path, 0, $position);
    }
}
