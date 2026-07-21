<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Support;

use OpenMeta\Support\Filesystem\FilesystemInterface;

/**
 * Renders and writes code stubs for the `make:*` scaffolding commands.
 */
final class StubGenerator
{
    public function __construct(private readonly FilesystemInterface $filesystem)
    {
    }

    /**
     * @param array<string, string> $replacements token => value
     */
    public function render(string $stub, array $replacements): string
    {
        return strtr($stub, $replacements);
    }

    /**
     * @param array<string, string> $replacements
     */
    public function generate(string $path, string $stub, array $replacements): void
    {
        $this->filesystem->put($path, $this->render($stub, $replacements));
    }

    public function exists(string $path): bool
    {
        return $this->filesystem->isFile($path);
    }
}
