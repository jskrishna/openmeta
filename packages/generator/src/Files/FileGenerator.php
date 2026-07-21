<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Files;

use OpenMeta\Support\Filesystem\FilesystemInterface;

/**
 * Writes generated files to disk, creating parent directories as needed.
 */
final class FileGenerator
{
    private readonly DirectoryGenerator $directories;

    public function __construct(
        private readonly FilesystemInterface $filesystem,
        ?DirectoryGenerator $directories = null,
    ) {
        $this->directories = $directories ?? new DirectoryGenerator($filesystem);
    }

    public function exists(string $path): bool
    {
        return $this->filesystem->isFile($path);
    }

    public function write(GeneratedFile $file): void
    {
        $this->directories->ensure($this->directoryOf($file->path));
        $this->filesystem->put($file->path, $file->contents);
    }

    private function directoryOf(string $path): string
    {
        $normalised = str_replace('\\', '/', $path);
        $position = strrpos($normalised, '/');

        return $position === false ? '' : substr($normalised, 0, $position);
    }
}
