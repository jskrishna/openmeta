<?php

declare(strict_types=1);

namespace OpenMeta\Security\Tokens;

use OpenMeta\Security\Contracts\SecureRandomInterface;
use OpenMeta\Security\Contracts\TokenGeneratorInterface;
use OpenMeta\Security\Exceptions\InvalidTokenException;
use OpenMeta\Security\Hashing\Hasher;
use OpenMeta\Support\Uuid\Uuid;

/**
 * Opaque token / API-key / secret generator and HMAC verifier.
 */
final class TokenGenerator implements TokenGeneratorInterface
{
    public function __construct(
        private readonly SecureRandomInterface $random,
        private readonly string $secret,
    ) {
        if ($secret === '') {
            throw new \InvalidArgumentException('Token secret must not be empty.');
        }
    }

    public function opaque(int $bytes = 32): string
    {
        return $this->random->token($bytes);
    }

    public function uuid(): string
    {
        return Uuid::v4();
    }

    public function signed(string $payload, int $ttlSeconds = 3600): string
    {
        $expires = time() + $ttlSeconds;
        $body = base64_encode($payload) . '.' . $expires;
        $sig = Hasher::hmac($body, $this->secret);

        return $body . '.' . $sig;
    }

    /**
     * @throws InvalidTokenException
     */
    public function verifySigned(string $token): string
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            throw new InvalidTokenException('Malformed token.');
        }

        [$encoded, $expires, $sig] = $parts;

        if (! ctype_digit($expires)) {
            throw new InvalidTokenException('Invalid token expiry.');
        }

        $body = $encoded . '.' . $expires;

        if (! Hasher::equals(Hasher::hmac($body, $this->secret), $sig)) {
            throw new InvalidTokenException('Invalid token signature.');
        }

        if ((int) $expires < time()) {
            throw new InvalidTokenException('Token expired.');
        }

        $payload = base64_decode($encoded, true);

        if ($payload === false) {
            throw new InvalidTokenException('Invalid token payload.');
        }

        return $payload;
    }
}
