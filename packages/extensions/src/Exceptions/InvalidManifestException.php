<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Exceptions;

/**
 * Thrown when an extension manifest is missing required data or malformed.
 */
final class InvalidManifestException extends ExtensionsException
{
    public static function missingField(string $field): self
    {
        return new self(sprintf('Extension manifest is missing required field [%s].', $field));
    }

    public static function invalidField(string $field, string $reason): self
    {
        return new self(sprintf('Extension manifest field [%s] is invalid: %s', $field, $reason));
    }

    public static function unreadable(string $path): self
    {
        return new self(sprintf('Extension manifest [%s] could not be read.', $path));
    }

    public static function invalidJson(string $path): self
    {
        return new self(sprintf('Extension manifest [%s] does not contain valid JSON.', $path));
    }
}
