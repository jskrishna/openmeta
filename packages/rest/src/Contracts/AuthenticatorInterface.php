<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Contracts;

use OpenMeta\Rest\Exceptions\AuthenticationException;
use OpenMeta\Rest\Requests\Request;

/**
 * Resolve the authenticated identity for a request.
 */
interface AuthenticatorInterface
{
    /**
     * @throws AuthenticationException when $required and no identity is resolved
     */
    public function authenticate(Request $request, bool $required = true): mixed;
}
