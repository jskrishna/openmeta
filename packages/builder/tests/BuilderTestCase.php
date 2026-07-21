<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Tests;

use OpenMeta\Admin\AdminServiceProvider;
use OpenMeta\Admin\Menus\MenuRegistry;
use OpenMeta\Admin\Screens\ScreenRegistry;
use OpenMeta\Builder\App\VisualBuilder;
use OpenMeta\Builder\Application\BuilderApplication;
use OpenMeta\Builder\Builder;
use OpenMeta\Builder\BuilderServiceProvider;
use OpenMeta\Builder\Canvas\Canvas;
use OpenMeta\Builder\Conditions\ConditionBuilder;
use OpenMeta\Builder\DragDrop\DragDrop;
use OpenMeta\Builder\Registry\ComponentRegistry;
use OpenMeta\Builder\Schema\SchemaManager;
use OpenMeta\Builder\Templates\TemplateRegistry;
use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Database\DatabaseServiceProvider;
use OpenMeta\Fields\FieldsServiceProvider;
use OpenMeta\Security\Capabilities\ArrayCapabilityChecker;
use OpenMeta\Security\Contracts\CapabilityCheckerInterface;
use OpenMeta\Security\Nonce\Nonce;
use OpenMeta\Security\Permissions\Gate;
use OpenMeta\Security\SecurityServiceProvider;
use OpenMeta\Ui\UiServiceProvider;
use OpenMeta\Validation\ValidationServiceProvider;

abstract class BuilderTestCase extends \PHPUnit\Framework\TestCase
{
    protected BuilderApplication $builder;

    protected Builder $facade;

    protected Canvas $canvas;

    protected DragDrop $dragDrop;

    protected TemplateRegistry $templates;

    protected ConditionBuilder $conditions;

    protected ComponentRegistry $registry;

    protected SchemaManager $schema;

    protected ArrayCapabilityChecker $capabilities;

    protected Gate $gate;

    protected Nonce $nonce;

    protected ScreenRegistry $screens;

    protected MenuRegistry $menus;

    protected EventDispatcherInterface $events;

    protected function setUp(): void
    {
        parent::setUp();

        $app = Bootstrap::run(
            [
                'app' => ['key' => 'builder-test-secret'],
                'database' => [
                    'default' => 'memory',
                    'connections' => [
                        'memory' => ['driver' => 'memory', 'prefix' => 'om_'],
                    ],
                ],
            ],
            [
                ValidationServiceProvider::class,
                SecurityServiceProvider::class,
                DatabaseServiceProvider::class,
                FieldsServiceProvider::class,
                UiServiceProvider::class,
                AdminServiceProvider::class,
                BuilderServiceProvider::class,
            ]
        );

        $this->builder = $app->get(BuilderApplication::class);
        $this->facade = $app->get(Builder::class);
        $this->canvas = $this->builder->canvas();
        $this->dragDrop = $this->builder->dragDrop();
        $this->templates = $app->get(TemplateRegistry::class);
        $this->conditions = $this->builder->conditions();
        $this->registry = $this->builder->registry();
        $this->schema = $this->builder->schema();
        /** @var ArrayCapabilityChecker $caps */
        $caps = $app->get(CapabilityCheckerInterface::class);
        $this->capabilities = $caps;
        $this->gate = $app->get(Gate::class);
        $this->nonce = $app->get(Nonce::class);
        $this->screens = $app->get(ScreenRegistry::class);
        $this->menus = $app->get(MenuRegistry::class);
        $this->events = $app->events();
    }

    protected function grant(string ...$capabilities): void
    {
        $this->capabilities->grant($capabilities);
        $this->gate->flushCache();
    }
}
