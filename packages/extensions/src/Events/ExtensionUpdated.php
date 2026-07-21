<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Events;

/**
 * Dispatched after an extension's manifest is replaced with a new version.
 */
final class ExtensionUpdated
{
    public function __construct(
        public readonly string $packageId,
        public readonly string $previousVersion,
        public readonly string $version,
    ) {
    }
}
