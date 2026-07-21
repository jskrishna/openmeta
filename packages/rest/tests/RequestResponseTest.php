<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Tests;

use OpenMeta\Rest\Requests\Request;
use OpenMeta\Rest\Responses\EmptyResponse;
use OpenMeta\Rest\Responses\ErrorResponse;
use OpenMeta\Rest\Responses\JsonResponse;

final class RequestResponseTest extends RestTestCase
{
    public function test_request_is_immutable_when_enriched(): void
    {
        $original = Request::create('GET', '/items', ['page' => '1']);
        $updated = $original->withAttributes(['id' => '5'])->withUser(['id' => 1]);

        self::assertNull($original->route('id'));
        self::assertSame('5', $updated->route('id'));
        self::assertNull($original->user());
        self::assertSame(['id' => 1], $updated->user());
    }

    public function test_json_response_envelope(): void
    {
        $response = JsonResponse::make(['name' => 'OpenMeta'], 200, ['version' => '1']);

        self::assertSame(200, $response->status());
        self::assertSame('OpenMeta', $response->toArray()['data']['name']);
        self::assertSame('1', $response->toArray()['meta']['version']);
    }

    public function test_error_response_envelope(): void
    {
        $response = ErrorResponse::make('Bad input', 422, 'validation_error', ['field' => 'name']);

        self::assertSame(422, $response->status());
        self::assertSame('validation_error', $response->toArray()['error']['code']);
        self::assertSame('name', $response->toArray()['error']['details']['field']);
    }

    public function test_empty_response_has_no_body(): void
    {
        $response = new EmptyResponse();

        self::assertSame(204, $response->status());
        self::assertSame('', $response->body());
    }
}
