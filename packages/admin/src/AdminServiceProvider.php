<?php

declare(strict_types=1);

namespace OpenMeta\Admin;

use OpenMeta\Admin\Application\AdminApplication;
use OpenMeta\Admin\Assets\AssetRegistry;
use OpenMeta\Admin\Cards\CardComponent;
use OpenMeta\Admin\Components\ComponentRegistry;
use OpenMeta\Admin\Dashboard\Dashboard;
use OpenMeta\Admin\Forms\AdminForm;
use OpenMeta\Admin\Layouts\LayoutManager;
use OpenMeta\Admin\Menus\MenuItem;
use OpenMeta\Admin\Menus\MenuRegistry;
use OpenMeta\Admin\Modals\ModalManager;
use OpenMeta\Admin\Navigation\MenuGroup;
use OpenMeta\Admin\Navigation\NavigationManager;
use OpenMeta\Admin\Notices\NoticeManager;
use OpenMeta\Admin\Pages\Page;
use OpenMeta\Admin\Pages\PageManager;
use OpenMeta\Admin\Screens\Screen;
use OpenMeta\Admin\Screens\ScreenRegistry;
use OpenMeta\Admin\Settings\SettingsRegistry;
use OpenMeta\Admin\Settings\SettingsStore;
use OpenMeta\Admin\Tables\AdminTable;
use OpenMeta\Admin\Tables\TableBuilder;
use OpenMeta\Admin\Themes\ThemeManager;
use OpenMeta\Admin\Toolbar\Toolbar;
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Fields\Contracts\FieldRendererInterface;
use OpenMeta\Security\Nonce\Nonce;
use OpenMeta\Security\Permissions\Gate;
use OpenMeta\Security\Permissions\Permission;
use OpenMeta\Ui\Components\Card;
use OpenMeta\Ui\Theme\Theme;

/**
 * Registers admin application, pages, navigation, layouts, and demo screens.
 */
final class AdminServiceProvider extends ServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        $container->singleton(MenuRegistry::class, static fn (): MenuRegistry => new MenuRegistry());
        $container->singleton(PageManager::class, static fn (): PageManager => new PageManager());
        $container->singleton(ScreenRegistry::class, static fn (): ScreenRegistry => new ScreenRegistry());
        $container->singleton(LayoutManager::class, static fn (): LayoutManager => new LayoutManager());
        $container->singleton(ComponentRegistry::class, static fn (): ComponentRegistry => new ComponentRegistry());
        $container->singleton(NoticeManager::class, static fn (): NoticeManager => new NoticeManager());
        $container->singleton(AssetRegistry::class, static fn (): AssetRegistry => new AssetRegistry());
        $container->singleton(Dashboard::class, static fn (): Dashboard => new Dashboard());
        $container->singleton(SettingsRegistry::class, static fn (): SettingsRegistry => new SettingsRegistry());
        $container->singleton(SettingsStore::class, static fn (): SettingsStore => new SettingsStore([
            'site_name' => 'OpenMeta',
        ]));

        $container->singleton(NavigationManager::class, static function (ContainerInterface $c): NavigationManager {
            return new NavigationManager($c->get(MenuRegistry::class));
        });

        $container->singleton(ThemeManager::class, static function (ContainerInterface $c): ThemeManager {
            return new ThemeManager($c->get(Theme::class));
        });

        $container->singleton(ModalManager::class, static function (ContainerInterface $c): ModalManager {
            return new ModalManager(
                $c->has(EventDispatcherInterface::class) ? $c->get(EventDispatcherInterface::class) : null
            );
        });

        $container->singleton(AdminApplication::class, static function (ContainerInterface $c): AdminApplication {
            return new AdminApplication(
                $c->get(PageManager::class),
                $c->get(NavigationManager::class),
                $c->get(LayoutManager::class),
                $c->get(ComponentRegistry::class),
                $c->get(NoticeManager::class),
                $c->get(ModalManager::class),
                $c->get(ThemeManager::class),
                $c->get(AssetRegistry::class),
                $c->get(Dashboard::class),
                $c->get(SettingsRegistry::class),
                $c->get(SettingsStore::class),
                $c->get(Gate::class),
                $c->get(Nonce::class),
                $c->has(FieldRendererInterface::class) ? $c->get(FieldRendererInterface::class) : null,
                $c->has(EventDispatcherInterface::class) ? $c->get(EventDispatcherInterface::class) : null,
            );
        });

        $container->singleton(Admin::class, static function (ContainerInterface $c): Admin {
            return new Admin(
                $c->get(AdminApplication::class),
                $c->get(PageManager::class),
                $c->get(ScreenRegistry::class),
                $c->get(Gate::class),
                $c->has(EventDispatcherInterface::class) ? $c->get(EventDispatcherInterface::class) : null,
            );
        });

        $container->alias(Admin::class, 'admin');
    }

    public function boot(ContainerInterface $container): void
    {
        /** @var NavigationManager $navigation */
        $navigation = $container->get(NavigationManager::class);
        /** @var PageManager $pages */
        $pages = $container->get(PageManager::class);
        /** @var ScreenRegistry $screens */
        $screens = $container->get(ScreenRegistry::class);
        /** @var Dashboard $dashboard */
        $dashboard = $container->get(Dashboard::class);
        /** @var SettingsRegistry $settings */
        $settings = $container->get(SettingsRegistry::class);
        /** @var SettingsStore $store */
        $store = $container->get(SettingsStore::class);
        /** @var Nonce $nonce */
        $nonce = $container->get(Nonce::class);
        /** @var ComponentRegistry $components */
        $components = $container->get(ComponentRegistry::class);
        /** @var AssetRegistry $assets */
        $assets = $container->get(AssetRegistry::class);
        /** @var AdminApplication $app */
        $app = $container->get(AdminApplication::class);

        $components->register('card', new CardComponent());

        $navigation->registerGroup(new MenuGroup('openmeta-core', 'OpenMeta', 10));

        $navigation->menus()->add(new MenuItem(
            'openmeta',
            'OpenMeta',
            Dashboard::SCREEN_ID,
            Permission::MANAGE,
            null,
            58
        ));
        $navigation->menus()->add(new MenuItem(
            'openmeta-settings',
            'Settings',
            'openmeta-settings',
            Permission::MANAGE_SETTINGS,
            'openmeta',
            20
        ));

        $assets->style('openmeta-admin', 'assets/css/admin.css');
        $assets->script('openmeta-admin', 'assets/js/admin.js', ['openmeta-admin']);

        $dashboard->registerWidget('health', static function () use ($navigation): string {
            return Card::render(
                'Status',
                '<p>Menus registered: ' . count($navigation->menus()->all()) . '</p>'
            );
        });

        $settings->section('general', 'General', [
            ['name' => 'site_name', 'label' => 'Site name', 'rules' => 'required|string|min:2'],
        ]);

        $pages->register(new Page(
            Dashboard::SCREEN_ID,
            'OpenMeta',
            Permission::MANAGE,
            'dashboard-grid',
            'Overview of your OpenMeta installation.',
            static fn (): string => $dashboard->render(),
        ));

        $pages->register(new Page(
            'openmeta-settings',
            'OpenMeta Settings',
            Permission::MANAGE_SETTINGS,
            'sidebar',
            'Configure global OpenMeta settings.',
            static function () use ($settings, $store, $nonce): string {
                $section = $settings->get('general');
                $values = [];
                foreach ($section['fields'] as $field) {
                    $values[$field['name']] = $store->get($field['name'], '');
                }

                $form = new AdminForm('openmeta-settings-general', $section['fields'], $nonce, $values);

                return Card::render($section['title'], $form->render());
            },
            sidebar: static fn (): string => '<p class="om-settings__help">Settings are validated on save.</p>',
        ));

        $pages->register(new Page(
            'openmeta-entries',
            'Entries',
            Permission::EDIT_CONTENT,
            'full-width',
            'Browse content entries.',
            static function () use ($app): string {
                $toolbar = (new Toolbar())
                    ->action('Add New', 'entry.create')
                    ->search('Search entries…')
                    ->render();

                $table = $app->table('entries')
                    ->column('name', 'Name')
                    ->column('status', 'Status')
                    ->rows([
                        ['name' => 'Demo Entry', 'status' => 'Draft'],
                        ['name' => 'Published Sample', 'status' => 'Published'],
                    ])
                    ->rowAction('Edit', 'entry.edit')
                    ->render();

                return $toolbar . Card::render('Entries', $table);
            },
            toolbar: static function (): string {
                return (new Toolbar())->action('Refresh', 'entries.refresh')->render();
            },
        ));

        // Legacy screen registry for backward-compatible renderScreen() callers.
        foreach ($pages->all() as $page) {
            $screens->register(new Screen(
                $page->id,
                $page->title,
                $page->permission,
                static function () use ($container, $page): string {
                    return $container->get(Admin::class)->renderPage($page);
                },
            ));
        }

        $screens->register(new Screen(
            'openmeta-builder',
            'Visual Builder',
            Permission::MANAGE,
            static fn (): string => Card::render('Builder', '<p>Builder mount slot (Phase 11).</p>'),
        ));

        $navigation->menus()->add(new MenuItem(
            'openmeta-builder',
            'Builder',
            'openmeta-builder',
            Permission::MANAGE,
            'openmeta',
            30
        ));
    }
}
