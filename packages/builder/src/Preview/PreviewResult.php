<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Preview;

/**
 * Preview descriptor — no UI rendering in the builder package.
 */
final class PreviewResult
{
    /**
     * @param list<array<string, mixed>> $fields
     */
    public function __construct(
        public readonly array $fields,
        public readonly int $totalNodes,
        public readonly int $visibleNodes,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'fields' => $this->fields,
            'total_nodes' => $this->totalNodes,
            'visible_nodes' => $this->visibleNodes,
        ];
    }
}
