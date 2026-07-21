<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Resources;

use OpenMeta\Database\Pagination\LengthAwarePaginator;
use OpenMeta\Rest\Contracts\ResourceInterface;
use OpenMeta\Rest\Contracts\TransformerInterface;
use OpenMeta\Rest\Pagination\PaginationMeta;

/**
 * Paginated list resource with meta envelope.
 */
final class PaginatedResource implements ResourceInterface
{
    public function __construct(
        private readonly LengthAwarePaginator $paginator,
        private readonly ?TransformerInterface $transformer = null,
    ) {
    }

    /**
     * @return array{data: list<array<string, mixed>|null>, meta: array<string, mixed>}
     */
    public function toArray(): array
    {
        $items = [];

        foreach ($this->paginator->items()->all() as $item) {
            if ($this->transformer !== null) {
                $items[] = $this->transformer->transform($item);

                continue;
            }

            $items[] = $item;
        }

        return [
            'data' => $items,
            'meta' => PaginationMeta::fromPaginator($this->paginator),
        ];
    }
}
