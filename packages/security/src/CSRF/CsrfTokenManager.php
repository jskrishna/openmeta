<?php

declare(strict_types=1);

namespace OpenMeta\Security\CSRF;

use OpenMeta\Security\Contracts\CsrfTokenManagerInterface;
use OpenMeta\Security\Contracts\SecureRandomInterface;
use OpenMeta\Security\Exceptions\CsrfException;
use OpenMeta\Security\Hashing\Hasher;

/**
 * Framework-level CSRF token manager (not WordPress nonces).
 */
final class CsrfTokenManager implements CsrfTokenManagerInterface
{
    private ?string $current = null;

    public function __construct(
        private readonly SecureRandomInterface $random,
        private readonly string $secret,
        private readonly int $ttlSeconds = 7200,
    ) {
        if ($secret === '') {
            throw new \InvalidArgumentException('CSRF secret must not be empty.');
        }
    }

    public function generate(): string
    {
        $id = $this->random->token(32);
        $expires = time() + $this->ttlSeconds;
        $body = $id . '.' . $expires;
        $this->current = $body . '.' . Hasher::hmac($body, $this->secret);

        return $this->current;
    }

    public function rotate(): string
    {
        return $this->generate();
    }

    public function current(): string
    {
        return $this->current ?? $this->generate();
    }

    public function isValid(string $token): bool
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return false;
        }

        [$id, $expires, $sig] = $parts;

        if ($id === '' || ! ctype_digit($expires)) {
            return false;
        }

        $body = $id . '.' . $expires;

        if (! Hasher::equals(Hasher::hmac($body, $this->secret), $sig)) {
            return false;
        }

        return (int) $expires >= time();
    }

    /**
     * @throws CsrfException
     */
    public function validate(string $token): void
    {
        if (! $this->isValid($token)) {
            throw new CsrfException('CSRF token validation failed.');
        }
    }
}
