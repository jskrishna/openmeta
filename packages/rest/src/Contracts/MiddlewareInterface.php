<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Contracts;

use OpenMeta\Rest\Requests\Request;
use OpenMeta\Rest\Responses\Response;

/**
 * HTTP middleware — wrap the next handler in the pipeline.
 */
interface MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response;
}
