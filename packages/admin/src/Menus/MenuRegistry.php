<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Menus;

/**
 * Registers top-level and submenu items (WP bridge later; in-memory for CI).
 */
final class MenuRegistry
{
    /** @var array<string, MenuItem> */
    private array $items = [];

    public function add(MenuItem $item): void
    {
        $this->items[$item->id] = $item;
    }

    public function has(string $id): bool
    {
        return isset($this->items[$id]);
    }

    public function get(string $id): ?MenuItem
    {
        return $this->items[$id] ?? null;
    }

    /** @return list<MenuItem> */
    public function all(): array
    {
        $items = array_values($this->items);
        usort(
            $items,
            static fn (MenuItem $a, MenuItem $b): int => $a->position <=> $b->position
        );

        return $items;
    }

    /** @return list<MenuItem> */
    public function children(string $parentId): array
    {
        return array_values(array_filter(
            $this->all(),
            static fn (MenuItem $item): bool => $item->parent === $parentId
        ));
    }
}
