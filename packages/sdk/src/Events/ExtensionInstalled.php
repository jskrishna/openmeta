<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Events;

/**
 * Dispatched after an extension is installed into the registry.
 */
final class ExtensionInstalled
{
    public function __construct(
        public readonly string $packageId,
        public readonly string $version,
    ) {
    }
}
