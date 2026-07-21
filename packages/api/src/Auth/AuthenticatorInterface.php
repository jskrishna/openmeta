<?php

declare(strict_types=1);

namespace OpenMeta\Api\Auth;

use OpenMeta\Api\Rest\Request;

/**
 * Establishes who is calling. Does not decide resource permissions.
 */
interface AuthenticatorInterface
{
    /**
     * @return mixed Authenticated identity (user id, array, object) or null when guest allowed
     *
     * @throws \OpenMeta\Api\Exceptions\AuthenticationException
     */
    public function authenticate(Request $request, bool $required): mixed;
}
