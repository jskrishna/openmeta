<?php

declare(strict_types=1);

namespace OpenMeta\Security\Contracts;

/**
 * Action-bound nonce helpers.
 */
interface NonceInterface
{
    public function create(string $action): string;

    public function verify(string $nonce, string $action): bool;

    public function check(string $nonce, string $action): void;

    public function field(string $action, string $name = '_wpnonce'): string;
}
