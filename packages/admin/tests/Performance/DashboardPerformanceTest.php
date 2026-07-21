<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Tests\Performance;

use OpenMeta\Admin\Dashboard\Dashboard;
use OpenMeta\Admin\Tests\AdminTestCase;
use OpenMeta\Tests\Phase12\AssertsPerformanceBudget;

final class DashboardPerformanceTest extends AdminTestCase
{
    use AssertsPerformanceBudget;

    public function test_dashboard_render_under_budget(): void
    {
        $this->grant('manage_options');
        $this->assertUnderMs(200.0, function (): void {
            for ($i = 0; $i < 20; $i++) {
                $this->admin->renderScreen(Dashboard::SCREEN_ID);
            }
        }, 'admin dashboard');
    }
}
