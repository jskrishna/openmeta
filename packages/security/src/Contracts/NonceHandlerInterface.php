<?php

declare(strict_types=1);

namespace OpenMeta\Security\Contracts;

/**
 * Create / verify action-bound nonces. Never log nonce values.
 */
interface NonceHandlerInterface
{
    public function create(string $action): string;

    public function verify(string $nonce, string $action): bool;
}
