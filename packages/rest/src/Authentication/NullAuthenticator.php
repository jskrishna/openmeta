<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Authentication;

use OpenMeta\Rest\Contracts\AuthenticatorInterface;
use OpenMeta\Rest\Exceptions\AuthenticationException;
use OpenMeta\Rest\Requests\Request;

/**
 * Default no-op authenticator — fails closed when authentication is required.
 */
final class NullAuthenticator implements AuthenticatorInterface
{
    public function authenticate(Request $request, bool $required = true): mixed
    {
        if ($required) {
            throw new AuthenticationException();
        }

        return null;
    }
}
