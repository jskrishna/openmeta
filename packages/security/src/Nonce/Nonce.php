<?php

declare(strict_types=1);

namespace OpenMeta\Security\Nonce;

use OpenMeta\Security\Contracts\NonceHandlerInterface;
use OpenMeta\Security\Contracts\NonceInterface;
use OpenMeta\Security\Exceptions\InvalidNonceException;

/**
 * Action-bound nonce helpers. Does not replace Authentication or Permissions.
 */
final class Nonce implements NonceInterface
{
    public function __construct(private readonly NonceHandlerInterface $handler)
    {
    }

    public function create(string $action): string
    {
        return $this->handler->create($action);
    }

    public function verify(string $nonce, string $action): bool
    {
        if ($nonce === '') {
            return false;
        }

        return $this->handler->verify($nonce, $action);
    }

    /**
     * @throws InvalidNonceException
     */
    public function check(string $nonce, string $action): void
    {
        if (! $this->verify($nonce, $action)) {
            throw new InvalidNonceException('Invalid or missing nonce.');
        }
    }

    /**
     * HTML hidden input markup (escaped attribute values).
     */
    public function field(string $action, string $name = '_wpnonce'): string
    {
        $nonce = htmlspecialchars($this->create($action), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $name = htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        return '<input type="hidden" name="' . $name . '" value="' . $nonce . '" />';
    }
}
