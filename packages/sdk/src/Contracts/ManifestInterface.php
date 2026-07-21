<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Contracts;

use OpenMeta\Sdk\Manifest\Dependency;
use OpenMeta\Sdk\Manifest\Requirements;

/**
 * Read contract for an extension manifest.
 *
 * A manifest is immutable metadata describing an extension: identity,
 * version window, service providers, and declared resources.
 */
interface ManifestInterface
{
    /**
     * Globally unique package id, conventionally "vendor/name".
     */
    public function packageId(): string;

    public function name(): string;

    public function vendor(): string;

    public function version(): string;

    public function description(): string;

    public function author(): string;

    public function license(): string;

    /**
     * @return list<Dependency>
     */
    public function dependencies(): array;

    public function minimumCoreVersion(): ?string;

    public function maximumCoreVersion(): ?string;

    /**
     * @return list<class-string>
     */
    public function providers(): array;

    /**
     * @return array<string, mixed>
     */
    public function assets(): array;

    /**
     * @return array<string, mixed>
     */
    public function commands(): array;

    /**
     * @return array<string, mixed>
     */
    public function configuration(): array;

    /**
     * @return list<string>
     */
    public function permissions(): array;

    /**
     * @return array<string, bool>
     */
    public function featureFlags(): array;

    public function requirements(): Requirements;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
