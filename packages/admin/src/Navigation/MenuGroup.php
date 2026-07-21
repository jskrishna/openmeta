<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Navigation;

/**
 * Groups related menu items for sorting and extension.
 */
final class MenuGroup
{
    /** @var list<string> */
    private array $itemIds = [];

    public function __construct(
        public readonly string $id,
        public readonly string $label,
        public readonly int $position = 10,
    ) {
    }

    public function add(string $menuItemId): void
    {
        $this->itemIds[] = $menuItemId;
    }

    /** @return list<string> */
    public function itemIds(): array
    {
        return $this->itemIds;
    }
}
