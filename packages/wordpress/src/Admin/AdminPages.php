<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Admin;

use OpenMeta\Admin\Admin;
use OpenMeta\Admin\Menus\MenuItem;
use OpenMeta\Security\Permissions\PermissionMap;
use OpenMeta\Wordpress\Filters\FilterBridge;
use OpenMeta\Wordpress\Runtime\WordPressRuntimeInterface;

/**
 * Maps Admin MenuRegistry → WordPress admin pages.
 */
final class AdminPages
{
    public function __construct(
        private readonly WordPressRuntimeInterface $wp,
        private readonly Admin $admin,
        private readonly FilterBridge $filters,
        private readonly PermissionMap $map = new PermissionMap(),
    ) {
    }

    public function register(): void
    {
        $this->wp->addAction('admin_menu', [$this, 'onAdminMenu']);
    }

    public function onAdminMenu(): void
    {
        $this->registerMenus();
    }

    private function registerMenus(): void
    {
        foreach ($this->admin->menus()->all() as $item) {
            $capability = $this->resolveCapability($item);

            if ($item->parent === null) {
                $this->wp->addMenuPage(
                    $item->title,
                    $item->title,
                    $capability,
                    $item->screenId,
                    [$this, 'renderAdminPage'],
                    'dashicons-database',
                    $item->position
                );
            } else {
                $this->wp->addSubmenuPage(
                    $item->parent,
                    $item->title,
                    $item->title,
                    $capability,
                    $item->screenId,
                    [$this, 'renderAdminPage'],
                );
            }
        }
    }

    public function renderAdminPage(): void
    {
        $screenId = $this->wp->getAdminPageSlug() ?? '';

        if ($screenId === '') {
            return;
        }

        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Admin package escapes internally.
        echo $this->admin->renderScreen($screenId);
    }

    private function resolveCapability(MenuItem $item): string
    {
        $mapped = $this->map->capabilitiesFor($item->permission);
        $cap = $mapped[0] ?? $item->permission;

        return (string) $this->filters->apply(FilterBridge::MENU_CAPABILITY, $cap, $item);
    }
}
