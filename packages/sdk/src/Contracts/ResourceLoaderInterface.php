<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Contracts;

use OpenMeta\Sdk\Resources\ResourceRegistration;
use OpenMeta\Sdk\Resources\ResourceType;

/**
 * Collects resources contributed by extensions so hosts can drain them.
 *
 * The SDK is deliberately a registration surface only — it never mounts a
 * resource into host internals itself (ADR-0008: core never depends on
 * extensions).
 */
interface ResourceLoaderInterface
{
    public function register(
        ResourceType $type,
        string $id,
        mixed $payload,
        ?string $extensionId = null,
    ): void;

    /**
     * @return list<ResourceRegistration>
     */
    public function all(): array;

    /**
     * @return list<ResourceRegistration>
     */
    public function ofType(ResourceType $type): array;

    /**
     * @return list<ResourceRegistration>
     */
    public function forExtension(string $extensionId): array;
}
