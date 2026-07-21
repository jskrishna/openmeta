<?php

declare(strict_types=1);

namespace OpenMeta\Security\Hashing;

use OpenMeta\Security\Contracts\PasswordHasherInterface;

/**
 * Password hashing via PHP password_hash / password_verify.
 */
final class PasswordHasher implements PasswordHasherInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function __construct(
        private readonly string $algo = PASSWORD_DEFAULT,
        private readonly array $options = [],
    ) {
    }

    public function hash(string $password): string
    {
        return password_hash($password, $this->algo, $this->options);
    }

    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function needsRehash(string $hash): bool
    {
        return password_needs_rehash($hash, $this->algo, $this->options);
    }
}
