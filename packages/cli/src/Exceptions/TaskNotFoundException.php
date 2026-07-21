<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Exceptions;

/**
 * Thrown when a task name is not registered.
 */
final class TaskNotFoundException extends CliException
{
    public static function named(string $name): self
    {
        return new self(sprintf('Task [%s] is not registered.', $name));
    }
}
