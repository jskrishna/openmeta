<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Tests\Fixtures;

use OpenMeta\Support\Filesystem\FilesystemInterface;

/**
 * In-memory filesystem for stub-generation tests.
 */
final class InMemoryFilesystem implements FilesystemInterface
{
    /**
     * @param array<string, string> $files
     */
    public function __construct(private array $files = [])
    {
    }

    public function exists(string $path): bool
    {
        return isset($this->files[$path]);
    }

    public function isFile(string $path): bool
    {
        return isset($this->files[$path]);
    }

    public function isDirectory(string $path): bool
    {
        return false;
    }

    public function get(string $path): string
    {
        return $this->files[$path] ?? '';
    }

    public function put(string $path, string $contents): void
    {
        $this->files[$path] = $contents;
    }

    public function delete(string $path): bool
    {
        unset($this->files[$path]);

        return true;
    }

    public function makeDirectory(string $path, int $mode = 0755, bool $recursive = true): bool
    {
        return true;
    }
}
