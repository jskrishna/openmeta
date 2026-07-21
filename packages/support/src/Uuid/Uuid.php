<?php

declare(strict_types=1);

namespace OpenMeta\Support\Uuid;

use OpenMeta\Support\Exceptions\UuidException;
use OpenMeta\Support\ValueObjects\UuidValue;
use Random\RandomException;

/**
 * UUID helpers. Default generator is RFC 4122 version 4 via CSPRNG ({@see random_bytes()}).
 */
final class Uuid
{
    private const PATTERN = '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';

    /**
     * Generate a random UUID v4.
     *
     * @throws UuidException When CSPRNG is unavailable
     */
    public static function v4(): string
    {
        try {
            $bytes = random_bytes(16);
        } catch (RandomException $e) {
            throw new UuidException('Unable to generate UUID: CSPRNG unavailable.', 0, $e);
        }

        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);

        $hex = bin2hex($bytes);

        return sprintf(
            '%s-%s-%s-%s-%s',
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20, 12)
        );
    }

    public static function isValid(string $uuid): bool
    {
        if ($uuid === self::nil()) {
            return true;
        }

        return preg_match(self::PATTERN, $uuid) === 1;
    }

    public static function nil(): string
    {
        return '00000000-0000-0000-0000-000000000000';
    }

    /**
     * Parse a UUID string into an immutable value object.
     *
     * @throws UuidException When the string is not a valid UUID
     */
    public static function parse(string $uuid): UuidValue
    {
        return UuidValue::from($uuid);
    }
}
