<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Application;

use OpenMeta\Admin\Assets\AssetRegistry;
use OpenMeta\Admin\Components\ComponentRegistry;
use OpenMeta\Admin\Dashboard\Dashboard;
use OpenMeta\Admin\Forms\FormBuilder;
use OpenMeta\Admin\Layouts\LayoutManager;
use OpenMeta\Admin\Modals\ModalManager;
use OpenMeta\Admin\Navigation\NavigationManager;
use OpenMeta\Admin\Notices\NoticeManager;
use OpenMeta\Admin\Pages\PageManager;
use OpenMeta\Admin\Settings\SettingsRegistry;
use OpenMeta\Admin\Settings\SettingsStore;
use OpenMeta\Admin\Tables\TableBuilder;
use OpenMeta\Admin\Themes\ThemeManager;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Fields\Contracts\FieldRendererInterface;
use OpenMeta\Security\Nonce\Nonce;
use OpenMeta\Security\Permissions\Gate;

/**
 * Admin application — boot, register pages, assets, components, navigation.
 */
final class AdminApplication
{
    public function __construct(
        private readonly PageManager $pages,
        private readonly NavigationManager $navigation,
        private readonly LayoutManager $layouts,
        private readonly ComponentRegistry $components,
        private readonly NoticeManager $notices,
        private readonly ModalManager $modals,
        private readonly ThemeManager $themes,
        private readonly AssetRegistry $assets,
        private readonly Dashboard $dashboard,
        private readonly SettingsRegistry $settings,
        private readonly SettingsStore $store,
        private readonly Gate $gate,
        private readonly Nonce $nonce,
        private readonly ?FieldRendererInterface $fieldRenderer = null,
        private readonly ?EventDispatcherInterface $events = null,
    ) {
    }

    public function pages(): PageManager
    {
        return $this->pages;
    }

    public function navigation(): NavigationManager
    {
        return $this->navigation;
    }

    public function layouts(): LayoutManager
    {
        return $this->layouts;
    }

    public function components(): ComponentRegistry
    {
        return $this->components;
    }

    public function notices(): NoticeManager
    {
        return $this->notices;
    }

    public function modals(): ModalManager
    {
        return $this->modals;
    }

    public function themes(): ThemeManager
    {
        return $this->themes;
    }

    public function assets(): AssetRegistry
    {
        return $this->assets;
    }

    public function dashboard(): Dashboard
    {
        return $this->dashboard;
    }

    public function settings(): SettingsRegistry
    {
        return $this->settings;
    }

    public function store(): SettingsStore
    {
        return $this->store;
    }

    public function gate(): Gate
    {
        return $this->gate;
    }

    public function form(string $id): FormBuilder
    {
        return new FormBuilder($id, $this->nonce, $this->fieldRenderer, $this->events);
    }

    public function table(string $id): TableBuilder
    {
        return new TableBuilder($id, $this->events);
    }
}
