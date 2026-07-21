<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Events;

use OpenMeta\Rest\Requests\Request;
use Throwable;

/**
 * Fired when an uncaught throwable is handled.
 */
final readonly class ExceptionThrown
{
    public function __construct(
        public Request $request,
        public Throwable $throwable,
    ) {
    }
}
