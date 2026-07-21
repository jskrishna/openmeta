<?php

declare(strict_types=1);

namespace OpenMeta\Core\Tests\Integration;

use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Core\Tests\CoreTestCase;

final class BootstrapGateTest extends CoreTestCase
{
    public function test_bootstrap_run_ready(): void
    {
        $app = Bootstrap::run(['app' => ['key' => 'phase12-core']]);
        $this->assertTrue($app->isBooted());
    }
}
