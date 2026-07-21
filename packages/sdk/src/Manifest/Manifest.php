<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Manifest;

use OpenMeta\Sdk\Contracts\ManifestInterface;

/**
 * Immutable extension manifest value object.
 *
 * Construct through {@see ManifestFactory} to get validation and array/JSON
 * parsing; the constructor itself performs no validation.
 */
final class Manifest implements ManifestInterface
{
    /**
     * @param list<Dependency>     $dependencies
     * @param list<class-string>   $providers
     * @param array<string, mixed> $assets
     * @param array<string, mixed> $commands
     * @param array<string, mixed> $configuration
     * @param list<string>         $permissions
     * @param array<string, bool>  $featureFlags
     */
    public function __construct(
        private readonly string $packageId,
        private readonly string $name,
        private readonly string $vendor,
        private readonly string $version,
        private readonly string $description = '',
        private readonly string $author = '',
        private readonly string $license = '',
        private readonly array $dependencies = [],
        private readonly ?string $minimumCoreVersion = null,
        private readonly ?string $maximumCoreVersion = null,
        private readonly array $providers = [],
        private readonly array $assets = [],
        private readonly array $commands = [],
        private readonly array $configuration = [],
        private readonly array $permissions = [],
        private readonly array $featureFlags = [],
        private readonly Requirements $requirements = new Requirements(),
    ) {
    }

    public function packageId(): string
    {
        return $this->packageId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function vendor(): string
    {
        return $this->vendor;
    }

    public function version(): string
    {
        return $this->version;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function author(): string
    {
        return $this->author;
    }

    public function license(): string
    {
        return $this->license;
    }

    public function dependencies(): array
    {
        return $this->dependencies;
    }

    public function minimumCoreVersion(): ?string
    {
        return $this->minimumCoreVersion;
    }

    public function maximumCoreVersion(): ?string
    {
        return $this->maximumCoreVersion;
    }

    public function providers(): array
    {
        return $this->providers;
    }

    public function assets(): array
    {
        return $this->assets;
    }

    public function commands(): array
    {
        return $this->commands;
    }

    public function configuration(): array
    {
        return $this->configuration;
    }

    public function permissions(): array
    {
        return $this->permissions;
    }

    public function featureFlags(): array
    {
        return $this->featureFlags;
    }

    public function requirements(): Requirements
    {
        return $this->requirements;
    }

    /**
     * Return a copy with a different version (used by updates).
     */
    public function withVersion(string $version): self
    {
        return new self(
            $this->packageId,
            $this->name,
            $this->vendor,
            $version,
            $this->description,
            $this->author,
            $this->license,
            $this->dependencies,
            $this->minimumCoreVersion,
            $this->maximumCoreVersion,
            $this->providers,
            $this->assets,
            $this->commands,
            $this->configuration,
            $this->permissions,
            $this->featureFlags,
            $this->requirements,
        );
    }

    public function toArray(): array
    {
        return [
            'packageId' => $this->packageId,
            'name' => $this->name,
            'vendor' => $this->vendor,
            'version' => $this->version,
            'description' => $this->description,
            'author' => $this->author,
            'license' => $this->license,
            'dependencies' => array_map(
                static fn (Dependency $dependency): array => $dependency->toArray(),
                $this->dependencies
            ),
            'minimumCoreVersion' => $this->minimumCoreVersion,
            'maximumCoreVersion' => $this->maximumCoreVersion,
            'providers' => $this->providers,
            'assets' => $this->assets,
            'commands' => $this->commands,
            'configuration' => $this->configuration,
            'permissions' => $this->permissions,
            'featureFlags' => $this->featureFlags,
            'requirements' => $this->requirements->toArray(),
        ];
    }
}
