<?php

declare(strict_types=1);

namespace OpenMeta\Api\Tests;

use OpenMeta\Api\Rest\Resources\JsonResource;
use OpenMeta\Api\Rest\Resources\ResourceCollection;

final class ResourceTest extends \PHPUnit\Framework\TestCase
{
    public function test_collection_pagination_envelope(): void
    {
        $collection = new ResourceCollection(
            [new JsonResource(['id' => 1]), ['id' => 2]],
            ['page' => 1, 'per_page' => 10, 'total' => 2]
        );

        $payload = $collection->toArray();
        self::assertCount(2, $payload['items']);
        self::assertSame(1, $payload['pagination']['page']);
        self::assertSame(2, $payload['pagination']['total']);
    }
}
