<?php

declare(strict_types=1);

namespace OpenMeta\Support\Paths;

use OpenMeta\Support\Exceptions\InvalidPathException;

/**
 * Cross-platform path join / normalize. Rejects null bytes; does not treat URLs as paths.
 */
final class Path
{
    public static function join(string ...$segments): string
    {
        $parts = [];

        foreach ($segments as $segment) {
            self::assertSafe($segment);

            if ($segment === '') {
                continue;
            }

            $parts[] = $segment;
        }

        if ($parts === []) {
            return '';
        }

        $first = array_shift($parts);
        $joined = $first;

        foreach ($parts as $part) {
            $joined = rtrim($joined, '/\\') . DIRECTORY_SEPARATOR . ltrim($part, '/\\');
        }

        return self::normalize($joined);
    }

    public static function normalize(string $path): string
    {
        self::assertSafe($path);

        $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);

        $drive = '';
        if (preg_match('/^([A-Za-z]:)(.*)$/', $path, $matches) === 1) {
            $drive = strtoupper($matches[1]);
            $path = $matches[2];
        }

        $isAbsolute = $drive !== '' || ($path !== '' && $path[0] === DIRECTORY_SEPARATOR);

        $parts = [];

        foreach (explode(DIRECTORY_SEPARATOR, $path) as $part) {
            if ($part === '' || $part === '.') {
                continue;
            }

            if ($part === '..') {
                if ($parts !== [] && end($parts) !== '..') {
                    array_pop($parts);
                    continue;
                }

                if (! $isAbsolute) {
                    $parts[] = '..';
                }

                continue;
            }

            $parts[] = $part;
        }

        $normalized = implode(DIRECTORY_SEPARATOR, $parts);

        if ($drive !== '') {
            return $drive . DIRECTORY_SEPARATOR . $normalized;
        }

        if ($isAbsolute) {
            return DIRECTORY_SEPARATOR . $normalized;
        }

        return $normalized;
    }

    public static function isAbsolute(string $path): bool
    {
        self::assertSafe($path);

        if ($path === '') {
            return false;
        }

        if ($path[0] === '/' || $path[0] === '\\') {
            return true;
        }

        return strlen($path) > 1 && ctype_alpha($path[0]) && $path[1] === ':';
    }

    public static function basename(string $path): string
    {
        self::assertSafe($path);

        return basename(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path));
    }

    public static function dirname(string $path): string
    {
        self::assertSafe($path);

        $normalized = self::normalize($path);
        $dir = dirname($normalized);

        if ($dir === '.' || $dir === $normalized) {
            return '';
        }

        return $dir;
    }

    public static function extension(string $path): string
    {
        self::assertSafe($path);

        return pathinfo($path, PATHINFO_EXTENSION);
    }

    private static function assertSafe(string $path): void
    {
        if (str_contains($path, "\0")) {
            throw new InvalidPathException('Path must not contain null bytes.');
        }
    }
}
