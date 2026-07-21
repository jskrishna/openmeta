<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Events;

/**
 * Dispatched immediately before a command executes.
 */
final class CommandStarted
{
    /**
     * @param array<string, mixed> $arguments
     * @param array<string, mixed> $options
     */
    public function __construct(
        public readonly string $command,
        public readonly array $arguments = [],
        public readonly array $options = [],
    ) {
    }
}
