<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Exceptions;

/**
 * Thrown when a stub template cannot be located.
 */
final class StubNotFoundException extends GeneratorException
{
    public static function named(string $name): self
    {
        return new self(sprintf('Stub [%s] could not be found in any registered stub path.', $name));
    }
}
