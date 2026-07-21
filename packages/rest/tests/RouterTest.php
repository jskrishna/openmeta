<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Tests;

use OpenMeta\Rest\Requests\Request;
use OpenMeta\Rest\Responses\JsonResponse;
use OpenMeta\Rest\Router\Router;
use OpenMeta\Rest\Routes\Route;

final class RouterTest extends RestTestCase
{
    public function test_registers_and_matches_route_with_params(): void
    {
        $this->router->version('v1');
        $handler = static function (Request $request): JsonResponse {
            return JsonResponse::make(['id' => $request->route('id')]);
        };
        $this->router->get('/users/{id}', $handler, 'users.show');

        $matched = $this->router->match(Request::create('GET', '/v1/users/42'));

        self::assertSame('42', $matched['params']['id']);
        self::assertSame('GET', $matched['route']->method());
    }

    public function test_group_applies_prefix_and_name(): void
    {
        $this->router->version('v1');
        $this->router->group(['prefix' => 'admin', 'name' => 'admin.'], function (Router $router): void {
            $router->get('/stats', static fn (): JsonResponse => JsonResponse::make(['ok' => true]), 'stats');
        });

        $route = $this->router->getByName('admin.stats');

        self::assertNotNull($route);
        self::assertSame('/admin/stats', $route->path());
    }

    public function test_http_method_helpers_register_routes(): void
    {
        $this->router->post('/items', static fn (): JsonResponse => JsonResponse::make(null, 201));
        $this->router->put('/items/{id}', static fn (): JsonResponse => JsonResponse::make(['updated' => true]));
        $this->router->patch('/items/{id}', static fn (): JsonResponse => JsonResponse::make(['patched' => true]));
        $this->router->delete('/items/{id}', static fn (): JsonResponse => JsonResponse::make(null, 204));
        $this->router->options(
            '/items',
            static fn (): JsonResponse => JsonResponse::make(['allowed' => ['GET', 'POST']])
        );

        self::assertCount(5, $this->router->routes());
    }

    public function test_discover_registers_iterable_routes(): void
    {
        $routes = [
            new Route('GET', '/ping', static fn (): JsonResponse => JsonResponse::make(['pong' => true])),
        ];

        $this->router->discover($routes);

        $matched = $this->router->match(Request::create('GET', '/ping'));

        self::assertSame('GET', $matched['route']->method());
    }
}
