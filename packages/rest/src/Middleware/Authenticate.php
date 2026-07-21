<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Middleware;

use OpenMeta\Rest\Contracts\AuthenticatorInterface;
use OpenMeta\Rest\Contracts\MiddlewareInterface;
use OpenMeta\Rest\Requests\Request;
use OpenMeta\Rest\Responses\Response;

/**
 * Resolve and attach the authenticated identity to the request.
 */
final class Authenticate implements MiddlewareInterface
{
    public function __construct(
        private readonly AuthenticatorInterface $authenticator,
        private readonly bool $required = true,
    ) {
    }

    public function priority(): MiddlewarePriority
    {
        return MiddlewarePriority::High;
    }

    public function handle(Request $request, callable $next): Response
    {
        $user = $this->authenticator->authenticate($request, $this->required);

        return $next($request->withUser($user));
    }
}
