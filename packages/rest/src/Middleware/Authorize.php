<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Middleware;

use OpenMeta\Rest\Authorization\GateAuthorizer;
use OpenMeta\Rest\Contracts\MiddlewareInterface;
use OpenMeta\Rest\Requests\Request;
use OpenMeta\Rest\Responses\Response;

/**
 * Enforce OpenMeta permissions via {@see GateAuthorizer}.
 */
final class Authorize implements MiddlewareInterface
{
    /**
     * @param list<string> $permissions
     */
    public function __construct(
        private readonly GateAuthorizer $authorizer,
        private readonly array $permissions = [],
    ) {
    }

    public function priority(): MiddlewarePriority
    {
        return MiddlewarePriority::Normal;
    }

    public function handle(Request $request, callable $next): Response
    {
        $this->authorizer->authorize($this->permissions);

        return $next($request);
    }
}
