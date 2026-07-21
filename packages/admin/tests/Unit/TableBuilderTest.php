<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Tests\Unit;

use OpenMeta\Admin\Events\TableLoaded;
use OpenMeta\Admin\Navigation\MenuGroup;
use OpenMeta\Admin\Tables\TableBuilder;
use OpenMeta\Admin\Tests\AdminTestCase;
use OpenMeta\Admin\Toolbar\Toolbar;
use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Database\Pagination\LengthAwarePaginator;
use OpenMeta\Validation\ValidationServiceProvider;
use OpenMeta\Security\SecurityServiceProvider;
use OpenMeta\Ui\UiServiceProvider;
use OpenMeta\Admin\AdminServiceProvider;

final class TableBuilderTest extends AdminTestCase
{
    public function test_table_search_sort_and_pagination(): void
    {
        $table = (new TableBuilder('demo'))
            ->column('name', 'Name')
            ->column('status', 'Status')
            ->rows([
                ['name' => 'Beta', 'status' => 'Draft'],
                ['name' => 'Alpha', 'status' => 'Published'],
            ])
            ->search('alpha')
            ->sort('name', 'asc');

        $html = $table->render();
        $this->assertStringContainsString('Alpha', $html);
        $this->assertStringNotContainsString('Beta', $html);
    }

    public function test_table_loaded_event_and_database_paginator(): void
    {
        $app = Bootstrap::run(
            ['app' => ['key' => 'admin-test-secret']],
            [
                ValidationServiceProvider::class,
                SecurityServiceProvider::class,
                UiServiceProvider::class,
                AdminServiceProvider::class,
            ]
        );

        $fired = false;
        $dispatcher = $app->get(EventDispatcherInterface::class);
        $dispatcher->listen(TableLoaded::class, static function (TableLoaded $event) use (&$fired): void {
            $fired = $event->totalRows === 5;
        });

        $paginator = new LengthAwarePaginator(
            [
                ['name' => 'One', 'status' => 'A'],
                ['name' => 'Two', 'status' => 'B'],
            ],
            5,
            2,
            1,
        );

        $html = $app->get(\OpenMeta\Admin\Application\AdminApplication::class)
            ->table('paged')
            ->column('name', 'Name')
            ->column('status', 'Status')
            ->paginator($paginator)
            ->render();

        $this->assertTrue($fired);
        $this->assertStringContainsString('Page 1 of 3', $html);
    }

    public function test_navigation_toolbar_and_groups(): void
    {
        $nav = $this->admin->application()->navigation();
        $nav->registerGroup(new MenuGroup('test', 'Test Group'));
        $nav->addBreadcrumb(new \OpenMeta\Admin\Navigation\Breadcrumb('Home', 'openmeta-dashboard'));

        $this->assertGreaterThanOrEqual(1, count($nav->groups()));
        $this->assertStringContainsString('Home', $nav->renderBreadcrumbs());

        $toolbar = (new Toolbar())->action('Save', 'save')->search('Find…')->render();
        $this->assertStringContainsString('om-toolbar', $toolbar);
    }
}
