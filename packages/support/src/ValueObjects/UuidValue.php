<?php

declare(strict_types=1);

namespace OpenMeta\Support\ValueObjects;

use OpenMeta\Support\Exceptions\UuidException;
use OpenMeta\Support\Uuid\Uuid;
use Stringable;

/**
 * Immutable UUID value object. Validates on construction.
 */
final class UuidValue implements Stringable
{
    private function __construct(private readonly string $value)
    {
    }

    /**
     * @throws UuidException When the string is not a valid UUID
     */
    public static function from(string $uuid): self
    {
        if (! Uuid::isValid($uuid)) {
            throw new UuidException(sprintf('Invalid UUID: %s', $uuid));
        }

        return new self(strtolower($uuid));
    }

    /**
     * @throws UuidException When CSPRNG is unavailable
     */
    public static function v4(): self
    {
        return self::from(Uuid::v4());
    }

    public static function nil(): self
    {
        return new self(Uuid::nil());
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
