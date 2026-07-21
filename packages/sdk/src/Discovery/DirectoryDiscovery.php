<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Discovery;

use OpenMeta\Sdk\Contracts\DiscoveryInterface;
use OpenMeta\Sdk\Manifest\ManifestFactory;
use OpenMeta\Support\Filesystem\FilesystemInterface;

/**
 * Discovers local extensions by reading manifest JSON files from disk.
 *
 * File paths are supplied explicitly (keeping the class filesystem-listing
 * agnostic and fully testable); {@see scan()} is a convenience for turning
 * a set of directories into manifest paths.
 */
final class DirectoryDiscovery implements DiscoveryInterface
{
    /**
     * @param list<string> $paths Absolute paths to manifest JSON files
     */
    public function __construct(
        private readonly FilesystemInterface $filesystem,
        private readonly ManifestFactory $factory,
        private readonly array $paths = [],
    ) {
    }

    public function discover(): array
    {
        $manifests = [];

        foreach ($this->paths as $path) {
            if (! $this->filesystem->isFile($path)) {
                continue;
            }

            $manifests[] = $this->factory->fromJson($this->filesystem->get($path), $path);
        }

        return $manifests;
    }

    /**
     * Expand a set of directories into manifest file paths.
     *
     * @param list<string> $directories
     *
     * @return list<string>
     */
    public static function scan(array $directories, string $filename = 'openmeta.extension.json'): array
    {
        $paths = [];

        foreach ($directories as $directory) {
            $matches = glob(rtrim($directory, '/\\') . DIRECTORY_SEPARATOR . '*' . DIRECTORY_SEPARATOR . $filename);

            foreach ($matches ?: [] as $match) {
                $paths[] = $match;
            }
        }

        return $paths;
    }
}
