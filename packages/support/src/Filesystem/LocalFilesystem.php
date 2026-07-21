<?php

declare(strict_types=1);

namespace OpenMeta\Support\Filesystem;

use OpenMeta\Support\Exceptions\FilesystemException;
use OpenMeta\Support\Paths\Path;

/**
 * Local disk filesystem. Paths are normalized; null bytes rejected via Path.
 */
final class LocalFilesystem implements FilesystemInterface
{
    public function exists(string $path): bool
    {
        return file_exists($this->safe($path));
    }

    public function isFile(string $path): bool
    {
        return is_file($this->safe($path));
    }

    public function isDirectory(string $path): bool
    {
        return is_dir($this->safe($path));
    }

    public function get(string $path): string
    {
        $safe = $this->safe($path);

        if (! is_file($safe)) {
            throw new FilesystemException(sprintf('File not found: %s', $path));
        }

        $contents = file_get_contents($safe);

        if ($contents === false) {
            throw new FilesystemException(sprintf('Unable to read file: %s', $path));
        }

        return $contents;
    }

    public function put(string $path, string $contents): void
    {
        $safe = $this->safe($path);
        $dir = Path::dirname($safe);

        if ($dir !== '' && ! is_dir($dir) && ! $this->makeDirectory($dir)) {
            throw new FilesystemException(sprintf('Unable to create directory for: %s', $path));
        }

        if (file_put_contents($safe, $contents) === false) {
            throw new FilesystemException(sprintf('Unable to write file: %s', $path));
        }
    }

    public function delete(string $path): bool
    {
        $safe = $this->safe($path);

        if (! file_exists($safe)) {
            return false;
        }

        if (is_dir($safe)) {
            return rmdir($safe);
        }

        return unlink($safe);
    }

    public function makeDirectory(string $path, int $mode = 0755, bool $recursive = true): bool
    {
        $safe = $this->safe($path);

        if (is_dir($safe)) {
            return true;
        }

        return mkdir($safe, $mode, $recursive);
    }

    private function safe(string $path): string
    {
        return Path::normalize($path);
    }
}
