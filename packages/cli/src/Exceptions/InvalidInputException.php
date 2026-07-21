<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Exceptions;

/**
 * Thrown when parsed input does not satisfy a command's definition.
 */
final class InvalidInputException extends CliException
{
    public static function missingArgument(string $name): self
    {
        return new self(sprintf('Missing required argument [%s].', $name));
    }

    public static function unknownOption(string $name): self
    {
        return new self(sprintf('Unknown option [--%s].', $name));
    }

    public static function optionNeedsValue(string $name): self
    {
        return new self(sprintf('Option [--%s] requires a value.', $name));
    }
}
