<?php

declare(strict_types=1);

namespace OpenMeta\Security\Contracts;

/**
 * Password hashing / verification (PHP password_* APIs).
 */
interface PasswordHasherInterface
{
    public function hash(string $password): string;

    public function verify(string $password, string $hash): bool;

    public function needsRehash(string $hash): bool;
}
