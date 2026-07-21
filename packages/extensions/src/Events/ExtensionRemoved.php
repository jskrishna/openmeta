<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Events;

/**
 * Dispatched after an extension is uninstalled and removed from the registry.
 */
final class ExtensionRemoved
{
    public function __construct(
        public readonly string $packageId,
        public readonly string $version,
    ) {
    }
}
