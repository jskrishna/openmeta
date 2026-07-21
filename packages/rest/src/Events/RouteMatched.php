<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Events;

use OpenMeta\Rest\Requests\Request;
use OpenMeta\Rest\Routes\Route;

/**
 * Fired after a route is matched.
 */
final readonly class RouteMatched
{
    /**
     * @param array<string, string> $params
     */
    public function __construct(
        public Request $request,
        public Route $route,
        public array $params,
    ) {
    }
}
