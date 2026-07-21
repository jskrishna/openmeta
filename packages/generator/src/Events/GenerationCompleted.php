<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Events;

/**
 * Dispatched after a generation run finishes.
 */
final class GenerationCompleted
{
    public function __construct(
        public readonly string $key,
        public readonly int $written,
        public readonly int $skipped,
    ) {
    }
}
