<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Tests\Fixtures;

use OpenMeta\Support\Filesystem\FilesystemInterface;

/**
 * In-memory filesystem for documentation validation/generation tests.
 */
final class InMemoryFilesystem implements FilesystemInterface
{
    /**
     * @param array<string, string> $files
     * @param list<string>          $directories
     */
    public function __construct(
        private array $files = [],
        private array $directories = [],
    ) {
    }

    public function exists(string $path): bool
    {
        return isset($this->files[$path]) || in_array($path, $this->directories, true);
    }

    public function isFile(string $path): bool
    {
        return isset($this->files[$path]);
    }

    public function isDirectory(string $path): bool
    {
        return in_array($path, $this->directories, true);
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
        $this->directories[] = $path;

        return true;
    }
}
