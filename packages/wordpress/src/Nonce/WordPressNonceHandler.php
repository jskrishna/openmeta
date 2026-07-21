<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Nonce;

use OpenMeta\Security\Contracts\NonceHandlerInterface;
use OpenMeta\Wordpress\Nonces\WordPressNonceHandler as NonceBridge;

/**
 * @deprecated Use {@see \OpenMeta\Wordpress\Nonces\WordPressNonceHandler}
 */
final class WordPressNonceHandler implements NonceHandlerInterface
{
    private readonly NonceBridge $handler;

    public function __construct()
    {
        $this->handler = new NonceBridge();
    }

    public function create(string $action): string
    {
        return $this->handler->create($action);
    }

    public function verify(string $nonce, string $action): bool
    {
        return $this->handler->verify($nonce, $action);
    }
}
