<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Discovery;

use OpenMeta\Sdk\Contracts\DiscoveryInterface;
use OpenMeta\Sdk\Manifest\ManifestFactory;
use OpenMeta\Support\Filesystem\FilesystemInterface;

/**
 * Discovers extensions installed through Composer.
 *
 * Reads `vendor/composer/installed.json` and treats any package of type
 * `openmeta-extension`, or carrying an `extra.openmeta` manifest block, as
 * an extension.
 */
final class ComposerDiscovery implements DiscoveryInterface
{
    private const EXTENSION_TYPE = 'openmeta-extension';

    public function __construct(
        private readonly FilesystemInterface $filesystem,
        private readonly ManifestFactory $factory,
        private readonly string $installedJsonPath,
    ) {
    }

    public function discover(): array
    {
        if (! $this->filesystem->isFile($this->installedJsonPath)) {
            return [];
        }

        /** @var mixed $decoded */
        $decoded = json_decode($this->filesystem->get($this->installedJsonPath), true);

        if (! is_array($decoded)) {
            return [];
        }

        $packages = $decoded['packages'] ?? $decoded;

        if (! is_array($packages)) {
            return [];
        }

        $manifests = [];

        foreach ($packages as $package) {
            if (! is_array($package)) {
                continue;
            }

            $manifest = $this->toManifestData($package);

            if ($manifest !== null) {
                $manifests[] = $this->factory->fromArray($manifest);
            }
        }

        return $manifests;
    }

    /**
     * @param array<string, mixed> $package
     *
     * @return array<string, mixed>|null
     */
    private function toManifestData(array $package): ?array
    {
        $type = isset($package['type']) && is_string($package['type']) ? $package['type'] : '';
        $extra = isset($package['extra']) && is_array($package['extra']) ? $package['extra'] : [];
        $openmeta = isset($extra['openmeta']) && is_array($extra['openmeta']) ? $extra['openmeta'] : null;

        if ($type !== self::EXTENSION_TYPE && $openmeta === null) {
            return null;
        }

        $packageId = isset($package['name']) && is_string($package['name']) ? $package['name'] : '';
        $version = isset($package['version']) && is_string($package['version']) ? $package['version'] : '0.0.0';
        [$vendor, $name] = $this->splitPackageId($packageId);

        $defaults = [
            'packageId' => $packageId,
            'name' => $name,
            'vendor' => $vendor,
            'version' => $version,
            'description' => isset($package['description']) && is_string($package['description'])
                ? $package['description']
                : '',
            'license' => $this->firstLicense($package),
        ];

        /** @var array<string, mixed> $manifest */
        $manifest = array_merge($defaults, $openmeta ?? []);

        return $manifest;
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function splitPackageId(string $packageId): array
    {
        if (str_contains($packageId, '/')) {
            [$vendor, $name] = explode('/', $packageId, 2);

            return [$vendor, $name];
        }

        return ['', $packageId];
    }

    /**
     * @param array<string, mixed> $package
     */
    private function firstLicense(array $package): string
    {
        $license = $package['license'] ?? null;

        if (is_string($license)) {
            return $license;
        }

        if (is_array($license) && isset($license[0]) && is_string($license[0])) {
            return $license[0];
        }

        return '';
    }
}
