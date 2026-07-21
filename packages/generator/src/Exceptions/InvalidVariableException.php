<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Exceptions;

/**
 * Thrown when a required generation variable is missing or invalid.
 */
final class InvalidVariableException extends GeneratorException
{
    public static function missing(string $name): self
    {
        return new self(sprintf('Required generation variable [%s] is missing.', $name));
    }
}
