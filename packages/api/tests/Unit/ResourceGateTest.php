<?php

declare(strict_types=1);

namespace OpenMeta\Api\Tests\Unit;

use OpenMeta\Api\Rest\Resources\JsonResource;
use OpenMeta\Api\Tests\ApiTestCase;

final class ResourceGateTest extends ApiTestCase
{
    public function test_json_resource_shape(): void
    {
        $resource = new JsonResource(['id' => 1, 'name' => 'x']);
        $this->assertSame(['id' => 1, 'name' => 'x'], $resource->toArray());
    }
}
