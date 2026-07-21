<?php

declare(strict_types=1);

namespace OpenMeta\Support\Tests\Integration;

use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Support\SupportServiceProvider;
use OpenMeta\Support\Tests\SupportTestCase;

final class SupportProviderGateTest extends SupportTestCase
{
    public function test_provider_boots(): void
    {
        $app = Bootstrap::run(['app' => ['key' => 'phase12-support']], [SupportServiceProvider::class]);
        $this->assertTrue($app->isBooted());
    }
}
