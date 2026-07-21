<?php

declare(strict_types=1);

namespace OpenMeta\Database\Pagination;

/**
 * Cursor pagination stub — architecture reserved for future drivers.
 */
final class CursorPaginator
{
    /**
     * @param list<array<string, mixed>> $items
     */
    public function __construct(
        private readonly array $items,
        private readonly ?string $nextCursor = null,
    ) {
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function items(): array
    {
        return $this->items;
    }

    public function nextCursor(): ?string
    {
        return $this->nextCursor;
    }
}
