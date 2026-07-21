<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Middleware;

use OpenMeta\Rest\Contracts\MiddlewareInterface;
use OpenMeta\Rest\Requests\Request;
use OpenMeta\Rest\Responses\Response;
use OpenMeta\Rest\Support\RequestValidator;

/**
 * Validate the incoming request payload against rules.
 */
final class ValidateRequest implements MiddlewareInterface
{
    /**
     * @param array<string, list<string>|string> $rules
     * @param array<string, string> $messages
     */
    public function __construct(
        private readonly RequestValidator $validator,
        private readonly array $rules,
        private readonly array $messages = [],
    ) {
    }

    public function handle(Request $request, callable $next): Response
    {
        $this->validator->validate($request, $this->rules, $this->messages);

        return $next($request);
    }
}
