<?php

declare(strict_types=1);

namespace OpenMeta\Admin;

use OpenMeta\Admin\Application\AdminApplication;
use OpenMeta\Admin\Dashboard\Dashboard;
use OpenMeta\Admin\Events\PageLoaded;
use OpenMeta\Admin\Exceptions\AdminException;
use OpenMeta\Admin\Menus\MenuRegistry;
use OpenMeta\Admin\Pages\Page;
use OpenMeta\Admin\Pages\PageManager;
use OpenMeta\Admin\Screens\ScreenRegistry;
use OpenMeta\Admin\Settings\SettingsRegistry;
use OpenMeta\Admin\Settings\SettingsStore;
use OpenMeta\Admin\Support\ScreenContext;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Security\Permissions\Gate;

/**
 * Capability-gated admin screen renderer (facade over {@see AdminApplication}).
 */
final class Admin
{
    public function __construct(
        private readonly AdminApplication $application,
        private readonly PageManager $pages,
        private readonly ScreenRegistry $screens,
        private readonly Gate $gate,
        private readonly ?EventDispatcherInterface $events = null,
    ) {
    }

    public function application(): AdminApplication
    {
        return $this->application;
    }

    public function renderScreen(string $screenId): string
    {
        if ($this->pages->has($screenId)) {
            return $this->renderPage($this->pages->get($screenId));
        }

        $screen = $this->screens->get($screenId);

        if ($this->gate->cannot($screen->permission)) {
            throw new AdminException('You do not have permission to access this screen.');
        }

        $renderer = $screen->renderer;
        $body = is_callable($renderer) ? (string) $renderer() : '';

        $html = '<div class="om-admin wrap">';
        $html .= $body;
        $html .= '</div>';

        return $this->application->themes()->wrap($html);
    }

    public function renderPage(Page $page): string
    {
        if ($this->gate->cannot($page->permission)) {
            throw new AdminException('You do not have permission to access this screen.');
        }

        $this->events?->dispatch(new PageLoaded($page));

        $context = new ScreenContext(
            $page->title,
            $page->description,
            $page->toolbar(),
            $page->content(),
            $page->sidebar(),
            $page->footer(),
            $page->actions(),
        );

        $body = $this->application->layouts()->render($page->layout, $context);
        $notices = $this->application->notices()->render();
        $breadcrumbs = $this->application->navigation()->renderBreadcrumbs();

        $html = '<div class="om-admin wrap">' . $breadcrumbs . $notices . $body . '</div>';

        return $this->application->themes()->wrap($html);
    }

    public function screens(): ScreenRegistry
    {
        return $this->screens;
    }

    public function pages(): PageManager
    {
        return $this->pages;
    }

    public function menus(): MenuRegistry
    {
        return $this->application->navigation()->menus();
    }

    public function dashboard(): Dashboard
    {
        return $this->application->dashboard();
    }

    public function settings(): SettingsRegistry
    {
        return $this->application->settings();
    }

    public function store(): SettingsStore
    {
        return $this->application->store();
    }
}
