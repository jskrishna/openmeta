<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Errors;

/**
 * Raised when a named type / query / mutation is not registered.
 */
final class TypeNotFoundException extends SchemaException
{
    public static function named(string $name): self
    {
        return new self(sprintf('GraphQL type [%s] is not registered.', $name));
    }

    public static function query(string $name): self
    {
        return new self(sprintf('GraphQL query [%s] is not registered.', $name));
    }

    public static function mutation(string $name): self
    {
        return new self(sprintf('GraphQL mutation [%s] is not registered.', $name));
    }

    public static function resolver(string $name): self
    {
        return new self(sprintf('GraphQL resolver [%s] is not registered.', $name));
    }
}
