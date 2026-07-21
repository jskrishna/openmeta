<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Exceptions;

/**
 * Thrown when an operation targets an extension that is not registered.
 */
final class ExtensionNotFoundException extends ExtensionsException
{
    public static function forId(string $packageId): self
    {
        return new self(sprintf('Extension [%s] is not registered.', $packageId));
    }
}
