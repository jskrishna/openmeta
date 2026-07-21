<?php

declare(strict_types=1);

namespace OpenMeta\Security\Contracts;

/**
 * Opaque / signed token factory.
 */
interface TokenGeneratorInterface
{
    public function opaque(int $bytes = 32): string;

    public function uuid(): string;

    public function signed(string $payload, int $ttlSeconds = 3600): string;

    public function verifySigned(string $token): string;
}
