<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Tests\Integration;

use OpenMeta\Admin\Dashboard\Dashboard;
use OpenMeta\Admin\Tests\AdminTestCase;

final class ScreenRenderGateTest extends AdminTestCase
{
    public function test_dashboard_screen_renders_when_allowed(): void
    {
        $this->grant('manage_options');
        $html = $this->admin->renderScreen(Dashboard::SCREEN_ID);
        $this->assertStringContainsString('OpenMeta', $html);
    }
}
