<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Layouts;

use OpenMeta\Builder\Exceptions\BuilderException;

/**
 * Composable layout engine — rows, columns, containers, sections, nesting.
 */
final class LayoutEngine
{
    /** @var array<string, LayoutNode> */
    private array $layouts = [];

    public function register(LayoutNode $layout): void
    {
        $this->layouts[$layout->id] = $layout;
    }

    public function has(string $id): bool
    {
        return isset($this->layouts[$id]);
    }

    public function get(string $id): LayoutNode
    {
        if (! isset($this->layouts[$id])) {
            throw new BuilderException(sprintf('Unknown layout [%s].', $id));
        }

        return $this->layouts[$id];
    }

    public function nest(string $parentId, string $childId): void
    {
        $parent = $this->get($parentId);
        if (in_array($childId, $parent->children, true)) {
            return;
        }

        $children = [...$parent->children, $childId];
        $this->layouts[$parentId] = new LayoutNode($parent->id, $parent->type, $children, $parent->responsive);
    }

    /** @return list<LayoutNode> */
    public function all(): array
    {
        return array_values($this->layouts);
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return array_map(static fn (LayoutNode $node): array => $node->toArray(), $this->layouts);
    }

    /**
     * @param array<int|string, mixed> $data
     */
    public function load(array $data): void
    {
        $this->layouts = [];
        foreach ($data as $row) {
            if (! is_array($row)) {
                continue;
            }

            $layout = LayoutNode::fromArray($row);
            $this->layouts[$layout->id] = $layout;
        }
    }
}
