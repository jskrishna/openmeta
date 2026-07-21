<?php

declare(strict_types=1);

namespace OpenMeta\Api\Tests\Performance;

use OpenMeta\Api\Rest\Request;
use OpenMeta\Api\Tests\ApiTestCase;
use OpenMeta\Tests\Phase12\AssertsPerformanceBudget;

final class RestDispatchPerformanceTest extends ApiTestCase
{
    use AssertsPerformanceBudget;

    public function test_health_dispatch_under_budget(): void
    {
        $api = $this->api;
        $this->assertUnderMs(200.0, static function () use ($api): void {
            for ($i = 0; $i < 50; $i++) {
                $api->handle(new Request('GET', '/openmeta/v1/health'));
            }
        }, 'api health dispatch');
    }
}
