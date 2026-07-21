<?php

declare(strict_types=1);

namespace OpenMeta\Security\Support\ConstantTime;

/**
 * Timing-safe string comparison helpers.
 */
final class Comparator
{
    public static function equals(string $known, string $user): bool
    {
        return hash_equals($known, $user);
    }
}
