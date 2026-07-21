<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Api;

use OpenMeta\Docgen\Configuration\DocgenConfiguration;
use OpenMeta\Support\Filesystem\FilesystemInterface;

/**
 * Generates one Markdown API page per package plus an index, from reflected
 * public types.
 */
final class ApiDocGenerator
{
    public function __construct(
        private readonly ApiScanner $scanner,
        private readonly ApiDocRenderer $renderer,
        private readonly FilesystemInterface $filesystem,
    ) {
    }

    /**
     * @return list<string> Written file paths
     */
    public function generate(DocgenConfiguration $config): array
    {
        $packagesDir = $config->path($config->packagesPath);
        $apiDir = $config->path($config->apiPath);
        $written = [];
        $counts = [];

        foreach ($this->packageDirectories($packagesDir) as $package) {
            $srcDir = $packagesDir . '/' . $package . '/src';
            $types = $this->scanner->scan($srcDir);

            if ($types === []) {
                continue;
            }

            $path = $apiDir . '/' . $package . '.md';
            $this->filesystem->put($path, $this->renderer->renderPackage($package, $types));
            $written[] = $path;
            $counts[$package] = count($types);
        }

        $indexPath = $apiDir . '/README.md';
        $this->filesystem->put($indexPath, $this->renderer->renderIndex($counts));
        $written[] = $indexPath;

        return $written;
    }

    /**
     * @return list<string>
     */
    private function packageDirectories(string $packagesDir): array
    {
        if (! is_dir($packagesDir)) {
            return [];
        }

        $entries = scandir($packagesDir);

        if ($entries === false) {
            return [];
        }

        $packages = [];

        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            if (is_dir($packagesDir . '/' . $entry . '/src')) {
                $packages[] = $entry;
            }
        }

        sort($packages);

        return $packages;
    }
}
