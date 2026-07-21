<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Tests\Integration;

use OpenMeta\Wordpress\Tests\WordpressTestCase;

final class PluginBootGateTest extends WordpressTestCase
{
    public function test_boot_registers_lifecycle_hooks(): void
    {
        $this->assertNotNull($this->plugin->boot($this->testConfig()));
        $this->assertArrayHasKey('admin_menu', $this->wp->actions);
        $this->assertArrayHasKey('rest_api_init', $this->wp->actions);
    }
}
