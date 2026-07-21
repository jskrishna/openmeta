<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Tests\Performance;

use OpenMeta\Tests\Phase12\AssertsPerformanceBudget;
use OpenMeta\Wordpress\Tests\WordpressTestCase;

final class BootPerformanceTest extends WordpressTestCase
{
    use AssertsPerformanceBudget;

    public function test_plugin_boot_under_budget(): void
    {
        $this->assertUnderMs(2000.0, function (): void {
            $this->plugin->boot($this->testConfig());
            $this->wp->doAction('admin_menu');
            $this->wp->doAction('rest_api_init');
            $this->wp->doAction('init');
        }, 'wordpress boot');
    }
}
