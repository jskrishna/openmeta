<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Exceptions;

/**
 * Thrown when a version or version constraint cannot be parsed.
 */
final class InvalidVersionException extends SdkException
{
    public static function emptyVersion(): self
    {
        return new self('Version string cannot be empty.');
    }

    public static function invalidVersion(string $version): self
    {
        return new self(sprintf('Version [%s] is not a valid semantic version.', $version));
    }

    public static function invalidConstraint(string $constraint): self
    {
        return new self(sprintf('Version constraint [%s] could not be parsed.', $constraint));
    }
}
