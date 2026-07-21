<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Navigation;

use OpenMeta\Admin\Menus\MenuItem;
use OpenMeta\Admin\Menus\MenuRegistry;
use OpenMeta\Security\Escaping\Escaper;

/**
 * Main/sub menus, groups, icons, and breadcrumbs.
 */
final class NavigationManager
{
    /** @var array<string, MenuGroup> */
    private array $groups = [];

    /** @var list<Breadcrumb> */
    private array $breadcrumbs = [];

    public function __construct(private readonly MenuRegistry $menus)
    {
    }

    public function menus(): MenuRegistry
    {
        return $this->menus;
    }

    public function registerGroup(MenuGroup $group): void
    {
        $this->groups[$group->id] = $group;
    }

    public function addBreadcrumb(Breadcrumb $crumb): void
    {
        $this->breadcrumbs[] = $crumb;
    }

    /** @return list<MenuGroup> */
    public function groups(): array
    {
        $groups = array_values($this->groups);
        usort(
            $groups,
            static fn (MenuGroup $a, MenuGroup $b): int => $a->position <=> $b->position
        );

        return $groups;
    }

    /** @return list<Breadcrumb> */
    public function breadcrumbs(): array
    {
        return $this->breadcrumbs;
    }

    public function renderBreadcrumbs(): string
    {
        if ($this->breadcrumbs === []) {
            return '';
        }

        $parts = [];

        foreach ($this->breadcrumbs as $crumb) {
            $parts[] = Escaper::html($crumb->label);
        }

        return '<nav class="om-breadcrumbs" aria-label="Breadcrumb">'
            . implode(' &rsaquo; ', $parts)
            . '</nav>';
    }

    /** @return list<MenuItem> */
    public function topLevel(): array
    {
        return array_values(array_filter(
            $this->menus->all(),
            static fn (MenuItem $item): bool => $item->parent === null
        ));
    }
}
