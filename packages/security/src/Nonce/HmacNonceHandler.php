<?php

declare(strict_types=1);

namespace OpenMeta\Security\Nonce;

use OpenMeta\Security\Contracts\NonceHandlerInterface;

/**
 * Pure-PHP HMAC nonces (WP-independent). Tick window ~12 hours, accepts previous tick.
 */
final class HmacNonceHandler implements NonceHandlerInterface
{
    private const TICK_SECONDS = 43200;

    public function __construct(private readonly string $secret)
    {
        if ($secret === '') {
            throw new \InvalidArgumentException('Nonce secret must not be empty.');
        }
    }

    public function create(string $action): string
    {
        return $this->hash($action, $this->tick());
    }

    public function verify(string $nonce, string $action): bool
    {
        $tick = $this->tick();

        if (hash_equals($this->hash($action, $tick), $nonce)) {
            return true;
        }

        return hash_equals($this->hash($action, $tick - 1), $nonce);
    }

    private function tick(): int
    {
        return (int) floor(time() / self::TICK_SECONDS);
    }

    private function hash(string $action, int $tick): string
    {
        return hash_hmac('sha256', $action . '|' . $tick, $this->secret);
    }
}
