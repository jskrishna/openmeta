<?php

declare(strict_types=1);

namespace OpenMeta\Api\Tests\Integration;

use OpenMeta\Api\Rest\Request;
use OpenMeta\Api\Tests\ApiTestCase;

final class RestKernelGateTest extends ApiTestCase
{
    public function test_health_route(): void
    {
        $response = $this->api->handle(new Request('GET', '/openmeta/v1/health'));
        $this->assertSame(200, $response->status());
    }
}
