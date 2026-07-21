<?php

declare(strict_types=1);

namespace OpenMeta\Core\Events;

/**
 * Dispatched when the core framework has finished booting.
 */
final class FrameworkBooted
{
    public function __construct(
        public readonly string $version,
    ) {
    }
}
