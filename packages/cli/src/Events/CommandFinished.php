<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Events;

/**
 * Dispatched after a command finishes with its exit code.
 */
final class CommandFinished
{
    public function __construct(
        public readonly string $command,
        public readonly int $exitCode,
    ) {
    }
}
