<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Packages;

use OpenMeta\Docgen\Configuration\DocgenConfiguration;
use OpenMeta\Support\Filesystem\FilesystemInterface;

/**
 * Builds the package index page from each package's composer.json.
 */
final class PackageDocGenerator
{
    public function __construct(private readonly FilesystemInterface $filesystem)
    {
    }

    /**
     * @return string Written path
     */
    public function generate(DocgenConfiguration $config): string
    {
        $packagesDir = $config->path($config->packagesPath);
        $rows = [];

        foreach ($this->packageDirectories($packagesDir) as $package) {
            $manifest = $packagesDir . '/' . $package . '/composer.json';

            if (! $this->filesystem->isFile($manifest)) {
                continue;
            }

            $rows[] = $this->rowFor($package, $this->filesystem->get($manifest));
        }

        // Generated table lives under reference/ so the hand-written package
        // guides index (docs/packages/README.md) is never clobbered.
        $path = $config->path($config->docsPath) . '/reference/packages.md';
        $this->filesystem->put($path, $this->render($rows));

        return $path;
    }

    /**
     * @param list<array{name: string, version: string, description: string}> $rows
     */
    public function render(array $rows): string
    {
        $lines = [];
        $lines[] = '# Packages';
        $lines[] = '';
        $lines[] = 'Every OpenMeta package, generated from `packages/*/composer.json`.';
        $lines[] = '';
        $lines[] = '| Package | Version | Description |';
        $lines[] = '| ------- | ------- | ----------- |';

        usort($rows, static fn (array $a, array $b): int => strcmp($a['name'], $b['name']));

        foreach ($rows as $row) {
            $lines[] = sprintf('| `%s` | %s | %s |', $row['name'], $row['version'], $row['description']);
        }

        return implode("\n", $lines) . "\n";
    }

    /**
     * @return array{name: string, version: string, description: string}
     */
    private function rowFor(string $package, string $composerJson): array
    {
        /** @var mixed $data */
        $data = json_decode($composerJson, true);
        $data = is_array($data) ? $data : [];

        return [
            'name' => isset($data['name']) && is_string($data['name']) ? $data['name'] : $package,
            'version' => isset($data['version']) && is_string($data['version']) ? $data['version'] : '—',
            'description' => isset($data['description']) && is_string($data['description']) ? $data['description'] : '',
        ];
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
            if ($entry !== '.' && $entry !== '..' && is_dir($packagesDir . '/' . $entry)) {
                $packages[] = $entry;
            }
        }

        sort($packages);

        return $packages;
    }
}
