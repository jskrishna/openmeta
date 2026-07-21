<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Events;

use OpenMeta\Rest\Requests\Request;

/**
 * Fired when a request enters the REST kernel.
 */
final readonly class RequestReceived
{
    public function __construct(public Request $request)
    {
    }
}
