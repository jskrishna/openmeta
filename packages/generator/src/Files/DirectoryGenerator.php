<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Files;

use OpenMeta\Support\Filesystem\FilesystemInterface;

/**
 * Ensures target directories exist before files are written.
 */
final class DirectoryGenerator
{
    public function __construct(private readonly FilesystemInterface $filesystem)
    {
    }

    public function ensure(string $directory): void
    {
        if ($directory === '' || $directory === '.' || $this->filesystem->isDirectory($directory)) {
            return;
        }

        $this->filesystem->makeDirectory($directory);
    }
}
