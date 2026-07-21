<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Errors;

/**
 * Raised when registering a name that already exists in a registry.
 */
final class DuplicateTypeException extends SchemaException
{
    public static function named(string $kind, string $name): self
    {
        return new self(sprintf('%s [%s] is already registered.', $kind, $name));
    }
}
