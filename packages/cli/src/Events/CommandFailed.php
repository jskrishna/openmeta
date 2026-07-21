<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Events;

use Throwable;

/**
 * Dispatched when a command throws.
 */
final class CommandFailed
{
    public function __construct(
        public readonly string $command,
        public readonly Throwable $exception,
    ) {
    }
}
