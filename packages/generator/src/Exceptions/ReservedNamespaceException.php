<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Exceptions;

/**
 * Thrown when generation targets a reserved (framework-owned) namespace.
 */
final class ReservedNamespaceException extends GeneratorException
{
    public static function forNamespace(string $namespace): self
    {
        return new self(sprintf('Namespace [%s] is reserved and cannot be generated into.', $namespace));
    }
}
