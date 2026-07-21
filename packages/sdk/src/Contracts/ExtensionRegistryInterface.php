<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Contracts;

use OpenMeta\Sdk\Exceptions\ExtensionNotFoundException;
use OpenMeta\Sdk\Lifecycle\ExtensionState;
use OpenMeta\Sdk\Registry\Extension;

/**
 * Stores registered extensions keyed by package id.
 */
interface ExtensionRegistryInterface
{
    public function add(Extension $extension): void;

    public function has(string $packageId): bool;

    /**
     * @throws ExtensionNotFoundException
     */
    public function get(string $packageId): Extension;

    public function find(string $packageId): ?Extension;

    public function remove(string $packageId): void;

    /**
     * @return list<Extension>
     */
    public function all(): array;

    /**
     * @return list<Extension>
     */
    public function byState(ExtensionState $state): array;
}
