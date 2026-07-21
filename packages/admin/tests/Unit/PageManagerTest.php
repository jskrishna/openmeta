<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Tests\Unit;

use OpenMeta\Admin\Events\PageLoaded;
use OpenMeta\Admin\Pages\Page;
use OpenMeta\Admin\Tests\AdminTestCase;
use OpenMeta\Security\Permissions\Permission;

final class PageManagerTest extends AdminTestCase
{
    public function test_registers_and_renders_page_with_layout(): void
    {
        $this->grant('manage_options');

        $page = new Page(
            'demo-page',
            'Demo',
            Permission::MANAGE,
            'full-width',
            'Demo page',
            static fn (): string => '<p>Page body</p>',
        );

        $this->admin->pages()->register($page);
        $html = $this->admin->renderPage($page);

        $this->assertStringContainsString('Page body', $html);
        $this->assertStringContainsString('Demo page', $html);
    }

    public function test_page_loaded_event_dispatched(): void
    {
        $this->grant('manage_options');
        $fired = false;

        $app = \OpenMeta\Core\Bootstrap\Bootstrap::run(
            ['app' => ['key' => 'admin-test-secret']],
            [
                \OpenMeta\Validation\ValidationServiceProvider::class,
                \OpenMeta\Security\SecurityServiceProvider::class,
                \OpenMeta\Ui\UiServiceProvider::class,
                \OpenMeta\Admin\AdminServiceProvider::class,
            ]
        );

        $dispatcher = $app->get(\OpenMeta\Core\Contracts\EventDispatcherInterface::class);
        $dispatcher->listen(PageLoaded::class, static function () use (&$fired): void {
            $fired = true;
        });

        $admin = $app->get(\OpenMeta\Admin\Admin::class);
        $caps = $app->get(\OpenMeta\Security\Contracts\CapabilityCheckerInterface::class);
        assert($caps instanceof \OpenMeta\Security\Capabilities\ArrayCapabilityChecker);
        $caps->grant(['manage_options']);
        $app->get(\OpenMeta\Security\Permissions\Gate::class)->flushCache();

        $admin->renderScreen(\OpenMeta\Admin\Dashboard\Dashboard::SCREEN_ID);

        $this->assertTrue($fired);
    }
}
