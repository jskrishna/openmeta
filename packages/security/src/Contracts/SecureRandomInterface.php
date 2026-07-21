<?php

declare(strict_types=1);

namespace OpenMeta\Security\Contracts;

/**
 * Cryptographically secure random bytes / strings.
 */
interface SecureRandomInterface
{
    public function bytes(int $length): string;

    public function hex(int $length): string;

    public function token(int $length = 32): string;
}
