<?php

declare(strict_types=1);

namespace OpenMeta\Api\Tests;

use OpenMeta\Api\Rest\Request;
use OpenMeta\Api\Rest\Routes\Router;

final class RestApiTest extends ApiTestCase
{
    public function test_health_is_public(): void
    {
        $response = $this->api->handle(new Request('GET', '/' . Router::NAMESPACE . '/health'));

        self::assertSame(200, $response->status());
        self::assertSame('ok', $response->toArray()['data']['status'] ?? null);
    }

    public function test_show_requires_authentication(): void
    {
        $response = $this->api->handle(new Request(
            'GET',
            '/' . Router::NAMESPACE . '/fields/post/1/title'
        ));

        self::assertSame(401, $response->status());
        self::assertSame('unauthenticated', $response->toArray()['error']['code'] ?? null);
    }

    public function test_show_requires_authorization(): void
    {
        $response = $this->api->handle(new Request(
            'GET',
            '/' . Router::NAMESPACE . '/fields/post/1/title',
            ['Authorization' => 'Bearer test-token'],
            [],
            ['type' => 'text']
        ));

        self::assertSame(403, $response->status());
    }

    public function test_put_and_get_field_value_happy_path(): void
    {
        $this->grant('edit_posts', 'read');

        $put = $this->api->handle(new Request(
            'PUT',
            '/' . Router::NAMESPACE . '/fields/post/10/title',
            ['Authorization' => 'Bearer test-token'],
            [],
            [
                'type' => 'text',
                'settings' => ['required' => true],
                'value' => 'Hello API',
            ]
        ));

        self::assertSame(200, $put->status());
        self::assertSame('Hello API', $put->toArray()['data']['value'] ?? null);

        $get = $this->api->handle(new Request(
            'GET',
            '/' . Router::NAMESPACE . '/fields/post/10/title',
            ['Authorization' => 'Bearer test-token'],
            [],
            ['type' => 'text']
        ));

        self::assertSame(200, $get->status());
        self::assertSame('Hello API', $get->toArray()['data']['value'] ?? null);
    }

    public function test_put_validation_error_envelope(): void
    {
        $this->grant('edit_posts');

        $response = $this->api->handle(new Request(
            'PUT',
            '/' . Router::NAMESPACE . '/fields/post/11/title',
            ['Authorization' => 'Bearer test-token'],
            [],
            [
                'type' => 'text',
                'settings' => ['required' => true],
                'value' => '',
            ]
        ));

        self::assertSame(422, $response->status());
        self::assertSame('validation_error', $response->toArray()['error']['code'] ?? null);
    }
}
