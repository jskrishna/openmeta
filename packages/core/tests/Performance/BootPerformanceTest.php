<?php

declare(strict_types=1);

namespace OpenMeta\Core\Tests\Performance;

use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Core\Tests\CoreTestCase;
use OpenMeta\Tests\Phase12\AssertsPerformanceBudget;

final class BootPerformanceTest extends CoreTestCase
{
    use AssertsPerformanceBudget;

    public function test_bootstrap_under_budget(): void
    {
        $this->assertUnderMs(500.0, static function (): void {
            Bootstrap::run(['app' => ['key' => 'perf-core']]);
        }, 'core boot');
    }
}
