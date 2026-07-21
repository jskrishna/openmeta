<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Resources;

/**
 * A single resource an extension contributes to a host.
 *
 * The payload is opaque to the SDK — its shape is defined by the host that
 * consumes the given {@see ResourceType} (e.g. a field factory, a route
 * definition, an asset descriptor).
 */
final class ResourceRegistration
{
    public function __construct(
        public readonly ResourceType $type,
        public readonly string $id,
        public readonly mixed $payload,
        public readonly ?string $extensionId = null,
    ) {
    }
}
