<?php

declare(strict_types=1);

namespace OpenMeta\Builder\DragDrop;

/**
 * Drag source descriptor (architecture only).
 */
final class DragSource
{
    public function __construct(
        public readonly string $id,
        public readonly string $kind,
        public readonly ?string $nodeId = null,
    ) {
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'kind' => $this->kind,
            'node_id' => $this->nodeId,
        ];
    }
}
