<?php

declare(strict_types=1);

namespace OpenMeta\Security\Hashing;

use OpenMeta\Security\Support\ConstantTime\Comparator;

/**
 * Generic HMAC / digest helpers (not for passwords).
 */
final class Hasher
{
    public static function sha256(string $value): string
    {
        return hash('sha256', $value);
    }

    public static function hmac(string $value, string $key, string $algo = 'sha256'): string
    {
        return hash_hmac($algo, $value, $key);
    }

    public static function equals(string $known, string $user): bool
    {
        return Comparator::equals($known, $user);
    }
}
