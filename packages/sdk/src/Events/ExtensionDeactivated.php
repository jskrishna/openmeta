<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Events;

/**
 * Dispatched when an active extension is deactivated back to the installed
 * state (a temporary, reversible stop).
 */
final class ExtensionDeactivated
{
    public function __construct(
        public readonly string $packageId,
        public readonly string $version,
    ) {
    }
}
