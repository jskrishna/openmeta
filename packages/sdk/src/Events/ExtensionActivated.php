<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Events;

/**
 * Dispatched after an extension's service providers have loaded and it
 * becomes active.
 */
final class ExtensionActivated
{
    public function __construct(
        public readonly string $packageId,
        public readonly string $version,
    ) {
    }
}
