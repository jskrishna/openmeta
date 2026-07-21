<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Tests\Security;

use OpenMeta\Admin\Dashboard\Dashboard;
use OpenMeta\Admin\Exceptions\AdminException;
use OpenMeta\Admin\Tests\AdminTestCase;

final class CapabilityGateTest extends AdminTestCase
{
    public function test_screen_denied_without_capability(): void
    {
        $this->expectException(AdminException::class);
        $this->admin->renderScreen(Dashboard::SCREEN_ID);
    }
}
