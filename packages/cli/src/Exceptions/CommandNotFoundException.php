<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Exceptions;

/**
 * Thrown when a command name is not registered.
 */
final class CommandNotFoundException extends CliException
{
    public static function named(string $name): self
    {
        return new self(sprintf('Command [%s] is not registered.', $name));
    }
}
