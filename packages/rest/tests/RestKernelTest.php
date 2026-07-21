<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Tests;

use OpenMeta\Rest\Contracts\AuthenticatorInterface;
use OpenMeta\Rest\Exceptions\AuthenticationException;
use OpenMeta\Rest\Requests\Request;
use OpenMeta\Rest\Responses\JsonResponse;
use OpenMeta\Security\Permissions\Permission;

final class RestKernelTest extends RestTestCase
{
    public function test_happy_path_dispatch(): void
    {
        $this->router->get('/hello', static fn (): JsonResponse => JsonResponse::make(['message' => 'Hello']));

        $response = $this->kernel->handle(Request::create('GET', '/hello'));

        self::assertSame(200, $response->status());
        self::assertSame('Hello', $response->toArray()['data']['message']);
    }

    public function test_returns_404_for_unknown_route(): void
    {
        $response = $this->kernel->handle(Request::create('GET', '/missing'));

        self::assertSame(404, $response->status());
        self::assertSame('not_found', $response->toArray()['error']['code']);
    }

    public function test_auth_required_route_denies_unauthenticated(): void
    {
        $this->router->get(
            '/secure',
            static fn (): JsonResponse => JsonResponse::make(['secret' => true]),
            null,
            true,
        );

        $response = $this->kernel->handle(Request::create('GET', '/secure'));

        self::assertSame(401, $response->status());
        self::assertSame('unauthenticated', $response->toArray()['error']['code']);
    }

    public function test_permission_protected_route_denies_without_capability(): void
    {
        $this->router->get(
            '/protected',
            static fn (): JsonResponse => JsonResponse::make(['ok' => true]),
            null,
            false,
            [Permission::READ],
        );

        $response = $this->kernel->handle(Request::create('GET', '/protected'));

        self::assertSame(403, $response->status());
        self::assertSame('forbidden', $response->toArray()['error']['code']);
    }

    public function test_permission_protected_route_allows_with_capability(): void
    {
        $this->grant('read');

        $this->router->get(
            '/protected',
            static fn (): JsonResponse => JsonResponse::make(['ok' => true]),
            null,
            false,
            [Permission::READ],
        );

        $response = $this->kernel->handle(Request::create('GET', '/protected'));

        self::assertSame(200, $response->status());
        self::assertTrue($response->toArray()['data']['ok']);
    }

    public function test_custom_authenticator_resolves_user(): void
    {
        $authenticator = new class implements AuthenticatorInterface {
            public function authenticate(Request $request, bool $required = true): mixed
            {
                if ($request->header('Authorization') === 'Bearer test') {
                    return ['id' => 99];
                }

                if ($required) {
                    throw new AuthenticationException();
                }

                return null;
            }
        };

        $kernel = new \OpenMeta\Rest\RestKernel(
            $this->router,
            $this->app->get(\OpenMeta\Rest\Middleware\Pipeline::class),
            $authenticator,
            $this->app->get(\OpenMeta\Rest\Authorization\GateAuthorizer::class),
            $this->app->get(\OpenMeta\Rest\Exceptions\ExceptionHandler::class),
            $this->app->get(\OpenMeta\Core\Contracts\EventDispatcherInterface::class),
            $this->app->container(),
        );

        $this->router->get(
            '/me',
            static fn (Request $request): JsonResponse => JsonResponse::make(['id' => $request->user()['id'] ?? null]),
            null,
            true,
        );

        $response = $kernel->handle(Request::create('GET', '/me', [], [], ['Authorization' => 'Bearer test']));

        self::assertSame(200, $response->status());
        self::assertSame(99, $response->toArray()['data']['id']);
    }
}
