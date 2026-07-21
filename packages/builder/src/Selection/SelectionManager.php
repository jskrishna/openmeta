<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Selection;

use OpenMeta\Builder\Canvas\Canvas;
use OpenMeta\Builder\Canvas\CanvasNode;
use OpenMeta\Builder\Exceptions\BuilderException;

/**
 * Selection layer — single and multi-select over canvas nodes.
 */
final class SelectionManager
{
    /** @var list<string> */
    private array $selectedIds = [];

    public function select(Canvas $canvas, ?string $id, bool $append = false): void
    {
        if ($id === null) {
            $this->selectedIds = [];

            return;
        }

        if ($canvas->find($id) === null) {
            throw new BuilderException(sprintf('Cannot select unknown node [%s].', $id));
        }

        if ($append) {
            if (! in_array($id, $this->selectedIds, true)) {
                $this->selectedIds[] = $id;
            }

            return;
        }

        $this->selectedIds = [$id];
    }

    public function toggle(Canvas $canvas, string $id): void
    {
        if (in_array($id, $this->selectedIds, true)) {
            $this->selectedIds = array_values(array_filter(
                $this->selectedIds,
                static fn (string $selected): bool => $selected !== $id
            ));

            return;
        }

        $this->select($canvas, $id, true);
    }

    public function clear(): void
    {
        $this->selectedIds = [];
    }

    /** @return list<string> */
    public function ids(): array
    {
        return $this->selectedIds;
    }

    public function primaryId(): ?string
    {
        return $this->selectedIds[0] ?? null;
    }

    public function primary(Canvas $canvas): ?CanvasNode
    {
        $id = $this->primaryId();

        return $id === null ? null : $canvas->find($id);
    }

    /** @return list<CanvasNode> */
    public function nodes(Canvas $canvas): array
    {
        $nodes = [];
        foreach ($this->selectedIds as $id) {
            $node = $canvas->find($id);
            if ($node !== null) {
                $nodes[] = $node;
            }
        }

        return $nodes;
    }

    public function isSelected(string $id): bool
    {
        return in_array($id, $this->selectedIds, true);
    }
}
