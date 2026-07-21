<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Pagination;

use OpenMeta\Database\Pagination\LengthAwarePaginator;

/**
 * Pagination metadata for JSON responses.
 */
final class PaginationMeta
{
    /**
     * @return array{
     *     total: int,
     *     per_page: int,
     *     current_page: int,
     *     last_page: int
     * }
     */
    public static function fromPaginator(LengthAwarePaginator $paginator): array
    {
        return [
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
        ];
    }
}
