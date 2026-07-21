<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Events;

use OpenMeta\Rest\Requests\Request;
use OpenMeta\Rest\Responses\Response;

/**
 * Fired before the response is returned to the caller.
 */
final readonly class ResponseGenerated
{
    public function __construct(
        public Request $request,
        public Response $response,
    ) {
    }
}
