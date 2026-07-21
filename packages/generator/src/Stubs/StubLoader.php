<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Stubs;

use OpenMeta\Generator\Contracts\StubLoaderInterface;
use OpenMeta\Generator\Exceptions\StubNotFoundException;
use OpenMeta\Support\Filesystem\FilesystemInterface;

/**
 * Loads `*.stub` templates from a list of search paths. Paths added later take
 * priority, so extensions can override the built-in stubs.
 */
final class StubLoader implements StubLoaderInterface
{
    /** @var list<string> */
    private array $paths;

    /**
     * @param list<string> $paths
     */
    public function __construct(
        private readonly FilesystemInterface $filesystem,
        array $paths = [],
    ) {
        $this->paths = array_values($paths);
    }

    public function addPath(string $path): void
    {
        array_unshift($this->paths, $path);
    }

    public function has(string $name): bool
    {
        return $this->locate($name) !== null;
    }

    public function load(string $name): string
    {
        $path = $this->locate($name);

        if ($path === null) {
            throw StubNotFoundException::named($name);
        }

        return $this->filesystem->get($path);
    }

    private function locate(string $name): ?string
    {
        foreach ($this->paths as $directory) {
            $path = rtrim($directory, '/\\') . '/' . $name . '.stub';

            if ($this->filesystem->isFile($path)) {
                return $path;
            }
        }

        return null;
    }
}
