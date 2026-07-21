<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Events;

/**
 * Dispatched when a generation run begins.
 */
final class GenerationStarted
{
    public function __construct(
        public readonly string $key,
        public readonly string $name,
        public readonly bool $dryRun,
    ) {
    }
}
