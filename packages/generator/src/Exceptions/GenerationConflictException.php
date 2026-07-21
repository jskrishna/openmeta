<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Exceptions;

/**
 * Thrown when generation cannot proceed because of an unresolved conflict.
 */
final class GenerationConflictException extends GeneratorException
{
    public static function fileExists(string $path): self
    {
        return new self(sprintf('File [%s] already exists. Use --force to overwrite.', $path));
    }
}
