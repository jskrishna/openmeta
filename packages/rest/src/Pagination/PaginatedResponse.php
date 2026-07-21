<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Pagination;

use OpenMeta\Database\Pagination\LengthAwarePaginator;
use OpenMeta\Rest\Contracts\TransformerInterface;
use OpenMeta\Rest\Resources\PaginatedResource;
use OpenMeta\Rest\Responses\JsonResponse;

/**
 * Factory for paginated JSON responses.
 */
final class PaginatedResponse
{
    public static function make(
        LengthAwarePaginator $paginator,
        ?TransformerInterface $transformer = null,
        int $status = 200,
    ): JsonResponse {
        $resource = new PaginatedResource($paginator, $transformer);
        $payload = $resource->toArray();

        return JsonResponse::make($payload['data'], $status, $payload['meta']);
    }
}
