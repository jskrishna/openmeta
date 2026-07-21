<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Events;

/**
 * Dispatched when an extension is disabled and blocked from activating.
 */
final class ExtensionDisabled
{
    public function __construct(
        public readonly string $packageId,
        public readonly string $version,
    ) {
    }
}
