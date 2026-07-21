<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Tests\Fixtures;

use OpenMeta\Rest\Contracts\MiddlewareInterface;
use OpenMeta\Rest\Requests\Request;
use OpenMeta\Rest\Responses\Response;

final class RecordingMiddleware implements MiddlewareInterface
{
    public function __construct(
        private OrderTrace $trace,
        private string $label,
        private bool $before,
    ) {
    }

    public function handle(Request $request, callable $next): Response
    {
        if ($this->before) {
            $this->trace->push($this->label);
        }

        $response = $next($request);

        if (! $this->before) {
            $this->trace->push($this->label);
        }

        return $response;
    }
}
