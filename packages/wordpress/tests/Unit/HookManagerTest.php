<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Tests\Unit;

use OpenMeta\Wordpress\Filters\FilterBridge;
use OpenMeta\Wordpress\Filters\FilterManager;
use OpenMeta\Wordpress\Hooks\ActionBridge;
use OpenMeta\Wordpress\Hooks\HookManager;
use OpenMeta\Wordpress\Tests\WordpressTestCase;

final class HookManagerTest extends WordpressTestCase
{
    public function test_hook_manager_registers_action(): void
    {
        $hooks = new HookManager($this->wp);
        $called = false;

        $hooks->addAction('openmeta_unit', static function () use (&$called): void {
            $called = true;
        });
        $hooks->doAction('openmeta_unit');

        $this->assertTrue($called);
    }

    public function test_filter_manager_applies_filter(): void
    {
        $filters = new FilterManager($this->wp);
        $filters->addFilter('openmeta_unit_filter', static fn (string $v): string => $v . '-filtered');

        $this->assertSame('value-filtered', $filters->applyFilters('openmeta_unit_filter', 'value'));
    }

    public function test_action_bridge_uses_named_callback(): void
    {
        $actions = new ActionBridge($this->wp);
        $ready = false;

        $this->wp->addAction(ActionBridge::READY, static function () use (&$ready): void {
            $ready = true;
        });

        $actions->register();
        $this->wp->doAction('plugins_loaded');

        $this->assertTrue($ready);
    }

    public function test_filter_bridge_still_applies_filters(): void
    {
        $filters = new FilterBridge($this->wp);
        $filters->on(FilterBridge::REST_NAMESPACE, static fn (string $ns): string => $ns . '-test');
        $filters->register();

        $this->assertSame('openmeta/v1-test', $filters->apply(FilterBridge::REST_NAMESPACE, 'openmeta/v1'));
    }
}
