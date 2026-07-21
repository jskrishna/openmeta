<?php

declare(strict_types=1);

namespace OpenMeta\Api\Rest\Resources;

/**
 * Paginated collection envelope.
 */
final class ResourceCollection implements ResourceInterface
{
    /**
     * @param list<array<string, mixed>|ResourceInterface> $items
     * @param array{page?: int, per_page?: int, total?: int} $pagination
     */
    public function __construct(
        private readonly array $items,
        private readonly array $pagination = [],
    ) {
    }

    public function toArray(): array
    {
        $data = [];

        foreach ($this->items as $item) {
            $data[] = $item instanceof ResourceInterface ? $item->toArray() : $item;
        }

        return [
            'items' => $data,
            'pagination' => [
                'page' => (int) ($this->pagination['page'] ?? 1),
                'per_page' => (int) ($this->pagination['per_page'] ?? count($data)),
                'total' => (int) ($this->pagination['total'] ?? count($data)),
            ],
        ];
    }
}
