<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Canvas;

use OpenMeta\Builder\Exceptions\BuilderException;
use OpenMeta\Builder\Selection\SelectionManager;

/**
 * Primary editing surface — nodes, workspace, and selection binding.
 */
final class Canvas
{
    /** @var list<CanvasNode> */
    private array $nodes = [];

    public function __construct(
        array $nodes = [],
        private Workspace $workspace = new Workspace(),
        private readonly SelectionManager $selection = new SelectionManager(),
    ) {
        $this->nodes = array_values($nodes);
    }

    public function workspace(): Workspace
    {
        return $this->workspace;
    }

    public function setWorkspace(Workspace $workspace): void
    {
        $this->workspace = $workspace;
    }

    public function selection(): SelectionManager
    {
        return $this->selection;
    }

    public function add(CanvasNode $node, ?int $index = null): void
    {
        if ($index === null || $index >= count($this->nodes)) {
            $this->nodes[] = $node;

            return;
        }

        array_splice($this->nodes, max(0, $index), 0, [$node]);
    }

    public function remove(string $id): void
    {
        $this->nodes = array_values(array_filter(
            $this->nodes,
            static fn (CanvasNode $node): bool => $node->id !== $id
        ));

        if ($this->selection->isSelected($id)) {
            $remaining = array_values(array_filter(
                $this->selection->ids(),
                static fn (string $selected): bool => $selected !== $id
            ));
            $this->selection->clear();
            foreach ($remaining as $selectedId) {
                $this->selection->select($this, $selectedId, true);
            }
        }
    }

    public function select(?string $id, bool $append = false): void
    {
        $this->selection->select($this, $id, $append);
    }

    public function selectedId(): ?string
    {
        return $this->selection->primaryId();
    }

    public function selected(): ?CanvasNode
    {
        return $this->selection->primary($this);
    }

    public function find(string $id): ?CanvasNode
    {
        foreach ($this->nodes as $node) {
            if ($node->id === $id) {
                return $node;
            }

            $nested = $this->findInChildren($node, $id);
            if ($nested !== null) {
                return $nested;
            }
        }

        return null;
    }

    public function replace(CanvasNode $node): void
    {
        foreach ($this->nodes as $i => $existing) {
            if ($existing->id === $node->id) {
                $this->nodes[$i] = $node;

                return;
            }
        }

        throw new BuilderException(sprintf('Unknown node [%s].', $node->id));
    }

    public function move(string $id, int $toIndex): void
    {
        $from = null;
        $node = null;

        foreach ($this->nodes as $i => $item) {
            if ($item->id === $id) {
                $from = $i;
                $node = $item;
                break;
            }
        }

        if ($from === null || $node === null) {
            throw new BuilderException(sprintf('Unknown node [%s].', $id));
        }

        array_splice($this->nodes, $from, 1);
        $toIndex = max(0, min($toIndex, count($this->nodes)));
        array_splice($this->nodes, $toIndex, 0, [$node]);
    }

    /** @return list<CanvasNode> */
    public function nodes(): array
    {
        return $this->nodes;
    }

    /** @param list<CanvasNode> $nodes */
    public function replaceNodes(array $nodes): void
    {
        $this->nodes = array_values($nodes);
        $this->selection->clear();
    }

    public function count(): int
    {
        return count($this->nodes);
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'workspace' => $this->workspace->toArray(),
            'nodes' => array_map(static fn (CanvasNode $n): array => $n->toArray(), $this->nodes),
        ];
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $nodes = [];
        if (isset($data['nodes']) && is_array($data['nodes'])) {
            foreach ($data['nodes'] as $row) {
                if (is_array($row)) {
                    $nodes[] = CanvasNode::fromArray($row);
                }
            }
        }

        $workspace = isset($data['workspace']) && is_array($data['workspace'])
            ? Workspace::fromArray($data['workspace'])
            : new Workspace();

        return new self($nodes, $workspace);
    }

    private function findInChildren(CanvasNode $node, string $id): ?CanvasNode
    {
        foreach ($node->children as $child) {
            if ($child->id === $id) {
                return $child;
            }

            $nested = $this->findInChildren($child, $id);
            if ($nested !== null) {
                return $nested;
            }
        }

        return null;
    }
}
