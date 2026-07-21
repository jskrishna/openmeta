<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Tests;

use OpenMeta\Database\Pagination\LengthAwarePaginator;
use OpenMeta\Rest\Contracts\TransformerInterface;
use OpenMeta\Rest\Pagination\PaginatedResponse;
use OpenMeta\Rest\Resources\JsonResource;
use OpenMeta\Rest\Resources\ResourceCollection;

final class ResourceTest extends RestTestCase
{
    public function test_json_resource_wraps_array(): void
    {
        $resource = new JsonResource(['id' => 1, 'name' => 'Item']);

        self::assertSame(1, $resource->toArray()['id']);
    }

    public function test_resource_collection_maps_items(): void
    {
        $collection = new ResourceCollection([
            ['id' => 1],
            new JsonResource(['id' => 2]),
        ]);

        self::assertCount(2, $collection->toArray());
        self::assertSame(2, $collection->toArray()[1]['id']);
    }

    public function test_paginated_response_includes_meta(): void
    {
        $paginator = new LengthAwarePaginator(
            [['id' => 1], ['id' => 2]],
            10,
            2,
            1,
        );

        $transformer = new class implements TransformerInterface {
            public function transform(mixed $value): ?array
            {
                return is_array($value) ? ['item_id' => $value['id']] : null;
            }
        };

        $response = PaginatedResponse::make($paginator, $transformer);

        $payload = $response->toArray();

        self::assertSame(1, $payload['data'][0]['item_id']);
        self::assertSame(10, $payload['meta']['total']);
        self::assertSame(2, $payload['meta']['per_page']);
    }
}
