<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Events;

/**
 * Dispatched after a file is written (or previewed in a dry run).
 */
final class FileGeneratedEvent
{
    public function __construct(
        public readonly string $path,
        public readonly bool $dryRun,
    ) {
    }
}
