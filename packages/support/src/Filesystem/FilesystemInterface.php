<?php

declare(strict_types=1);

namespace OpenMeta\Support\Filesystem;

/**
 * Local filesystem contract. Always prefer {@see \OpenMeta\Support\Paths\Path} for joining paths.
 */
interface FilesystemInterface
{
    public function exists(string $path): bool;

    public function isFile(string $path): bool;

    public function isDirectory(string $path): bool;

    public function get(string $path): string;

    public function put(string $path, string $contents): void;

    public function delete(string $path): bool;

    public function makeDirectory(string $path, int $mode = 0755, bool $recursive = true): bool;
}
