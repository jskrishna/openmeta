<?php

declare(strict_types=1);

namespace OpenMeta\Builder\DragDrop;

/**
 * Drop target descriptor (architecture only).
 */
final class DropTarget
{
    /**
     * @param list<string> $accepts
     */
    public function __construct(
        public readonly string $id,
        public readonly array $accepts,
        public readonly ?int $index = null,
        public readonly ?string $parentId = null,
    ) {
    }

    public function accepts(string $kind): bool
    {
        return in_array($kind, $this->accepts, true) || in_array('*', $this->accepts, true);
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'accepts' => $this->accepts,
            'index' => $this->index,
            'parent_id' => $this->parentId,
        ];
    }
}
