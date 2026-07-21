<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Exceptions;

/**
 * Thrown when extension dependencies cannot be resolved.
 */
final class DependencyException extends ExtensionsException
{
    public static function missing(string $packageId, string $dependency): self
    {
        return new self(sprintf(
            'Extension [%s] requires [%s], which is not available.',
            $packageId,
            $dependency
        ));
    }

    public static function versionMismatch(string $packageId, string $dependency, string $constraint): self
    {
        return new self(sprintf(
            'Extension [%s] requires [%s] matching [%s], which is not satisfied.',
            $packageId,
            $dependency,
            $constraint
        ));
    }

    /**
     * @param list<string> $cycle
     */
    public static function circular(array $cycle): self
    {
        return new self(sprintf(
            'Circular extension dependency detected: %s.',
            implode(' -> ', $cycle)
        ));
    }
}
