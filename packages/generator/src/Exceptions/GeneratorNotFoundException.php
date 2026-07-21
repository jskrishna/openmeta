<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Exceptions;

/**
 * Thrown when a generator key is not registered.
 */
final class GeneratorNotFoundException extends GeneratorException
{
    public static function forKey(string $key): self
    {
        return new self(sprintf('Generator [%s] is not registered.', $key));
    }
}
