<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Tests;

use OpenMeta\Admin\Menus\MenuRegistry;
use OpenMeta\Api\Rest\Routes\Router;
use OpenMeta\Wordpress\Capabilities\CapabilityRegistrar;
use OpenMeta\Wordpress\Filters\FilterBridge;
use OpenMeta\Wordpress\Gutenberg\BlockRegistrar;
use OpenMeta\Wordpress\Hooks\ActionBridge;
use OpenMeta\Wordpress\Plugin\Plugin;
use OpenMeta\Wordpress\Plugin\Requirements;
use OpenMeta\Wordpress\Runtime\ArrayWordPressRuntime;
use OpenMeta\Wordpress\Runtime\NativeWordPressRuntime;

final class WordpressIntegrationTest extends WordpressTestCase
{
    public function test_requirements_gate_php_and_wp(): void
    {
        $req = new Requirements();
        $this->assertTrue($req->passes('8.3.0', '6.4.0'));
        $this->assertNotEmpty($req->check('8.2.0', '6.4.0'));
        $this->assertNotEmpty($req->check('8.3.0', '6.0.0'));
    }

    public function test_plugin_boot_registers_hooks_admin_rest_gutenberg(): void
    {
        $app = $this->plugin->boot($this->testConfig());
        $this->assertNotNull($app);
        $this->assertTrue($this->plugin->isBooted());

        $this->assertArrayHasKey('plugins_loaded', $this->wp->actions);
        $this->assertArrayHasKey('admin_menu', $this->wp->actions);
        $this->assertArrayHasKey('rest_api_init', $this->wp->actions);
        $this->assertArrayHasKey('init', $this->wp->actions);

        $this->wp->doAction('admin_menu');
        $this->assertNotEmpty($this->wp->menus);

        $this->wp->doAction('rest_api_init');
        $this->assertNotEmpty($this->wp->restRoutes);
        $this->assertSame(Router::NAMESPACE, $this->wp->restRoutes[0]['namespace']);

        $this->wp->doAction('init');
        $blockNames = array_column($this->wp->blocks, 'name');
        $this->assertContains(BlockRegistrar::FIELD_BLOCK, $blockNames);
        $this->assertContains(BlockRegistrar::SCHEMA_BLOCK, $blockNames);
    }

    public function test_hooks_and_filters_bridge(): void
    {
        $actions = new ActionBridge($this->wp);
        $filters = new FilterBridge($this->wp);

        $fired = false;
        $actions->on('openmeta_test', static function () use (&$fired): void {
            $fired = true;
        });
        $actions->register();
        $this->wp->doAction('openmeta_test');
        $this->assertTrue($fired);

        $filters->on(FilterBridge::REST_NAMESPACE, static fn (string $ns): string => $ns . '-x');
        $filters->register();
        $this->assertSame('openmeta/v1-x', $filters->apply(FilterBridge::REST_NAMESPACE, 'openmeta/v1'));
    }

    public function test_capabilities_on_activate(): void
    {
        $this->plugin->activate();
        $this->assertNotEmpty($this->wp->capabilities);
        $caps = array_column($this->wp->capabilities, 'capability');
        $this->assertContains('openmeta.manage', $caps);
        $this->assertContains('manage_options', $caps);
    }

    public function test_boot_is_idempotent(): void
    {
        $first = $this->plugin->boot($this->testConfig());
        $second = $this->plugin->boot($this->testConfig());
        $this->assertSame($first, $second);
    }

    public function test_admin_menus_include_openmeta_screens(): void
    {
        $this->plugin->boot($this->testConfig());
        $this->wp->doAction('admin_menu');

        $slugs = array_column($this->wp->menus, 'menu_slug');
        $this->assertContains('openmeta-builder', $slugs);

        /** @var MenuRegistry $menus */
        $menus = $this->plugin->app()?->get(MenuRegistry::class);
        $this->assertTrue($menus->has('openmeta-builder'));
    }

    public function test_native_runtime_safe_without_wordpress(): void
    {
        $native = new NativeWordPressRuntime();
        $this->assertFalse($native->isLoaded());
        $native->addAction('init', static fn () => null);
        $native->addFilter('the_title', static fn (string $t): string => $t);
        $this->assertSame('x', $native->applyFilters('the_title', 'x'));
        $this->assertFalse($native->registerRestRoute('openmeta/v1', '/health', []));
        $this->assertFalse($native->registerBlockType('openmeta/field'));
    }

    public function test_capability_registrar_lists_permissions(): void
    {
        $registrar = new CapabilityRegistrar($this->wp);
        $this->assertContains('openmeta.read', $registrar->permissions());
    }

    public function test_boot_hook_registration_budget(): void
    {
        $start = hrtime(true);
        $this->plugin->boot($this->testConfig());
        $this->wp->doAction('admin_menu');
        $this->wp->doAction('rest_api_init');
        $this->wp->doAction('init');
        $elapsedMs = (hrtime(true) - $start) / 1e6;

        $this->assertLessThan(2000.0, $elapsedMs, 'WordPress integration boot budget exceeded');
    }

    public function test_version_constant(): void
    {
        $this->assertSame('0.8.0-alpha', Plugin::VERSION);
    }
}
