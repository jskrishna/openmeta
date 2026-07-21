<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Events;

use OpenMeta\Rest\Requests\Request;
use OpenMeta\Rest\Responses\Response;
use OpenMeta\Rest\Routes\Route;

/**
 * Fired after the controller/callable returns a response.
 */
final readonly class ControllerExecuted
{
    public function __construct(
        public Request $request,
        public Route $route,
        public Response $response,
    ) {
    }
}
