<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Registry;

use OpenMeta\Extensions\Contracts\ExtensionRegistryInterface;
use OpenMeta\Extensions\Exceptions\ExtensionNotFoundException;
use OpenMeta\Extensions\Lifecycle\ExtensionState;

/**
 * In-memory extension registry.
 */
final class ExtensionRegistry implements ExtensionRegistryInterface
{
    /** @var array<string, Extension> */
    private array $extensions = [];

    public function add(Extension $extension): void
    {
        $this->extensions[$extension->id()] = $extension;
    }

    public function has(string $packageId): bool
    {
        return isset($this->extensions[$packageId]);
    }

    public function get(string $packageId): Extension
    {
        return $this->extensions[$packageId]
            ?? throw ExtensionNotFoundException::forId($packageId);
    }

    public function find(string $packageId): ?Extension
    {
        return $this->extensions[$packageId] ?? null;
    }

    public function remove(string $packageId): void
    {
        unset($this->extensions[$packageId]);
    }

    public function all(): array
    {
        return array_values($this->extensions);
    }

    public function byState(ExtensionState $state): array
    {
        return array_values(array_filter(
            $this->extensions,
            static fn (Extension $extension): bool => $extension->state() === $state,
        ));
    }
}
