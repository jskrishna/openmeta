<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Controllers;

use OpenMeta\Rest\Contracts\ResourceInterface;
use OpenMeta\Rest\Responses\EmptyResponse;
use OpenMeta\Rest\Responses\ErrorResponse;
use OpenMeta\Rest\Responses\JsonResponse;

/**
 * Base REST controller helpers.
 */
abstract class Controller
{
    /**
     * @param array<string, mixed>|list<mixed>|null $data
     * @param array<string, mixed> $meta
     */
    protected function json(mixed $data = null, int $status = 200, array $meta = []): JsonResponse
    {
        return JsonResponse::make($data, $status, $meta);
    }

    /**
     * @param array<string, mixed> $details
     */
    protected function error(
        string $message,
        int $status = 400,
        string $code = 'error',
        array $details = [],
    ): ErrorResponse {
        return ErrorResponse::make($message, $status, $code, $details);
    }

    protected function empty(int $status = 204): EmptyResponse
    {
        return new EmptyResponse($status);
    }

    protected function resource(ResourceInterface $resource, int $status = 200): JsonResponse
    {
        return JsonResponse::make($resource->toArray(), $status);
    }
}
