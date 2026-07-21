<?php

declare(strict_types=1);

namespace OpenMeta\Api\Rest;

use OpenMeta\Api\Auth\AuthenticatorInterface;
use OpenMeta\Api\Authz\Authorizer;
use OpenMeta\Api\Exceptions\ApiException;
use OpenMeta\Api\Rest\Routes\Route;
use OpenMeta\Api\Rest\Routes\Router;
use OpenMeta\Core\Contracts\ContainerInterface;

/**
 * Dispatches REST requests: match → Authentication → Authorization → Controller → Response.
 */
final class RestKernel
{
    public function __construct(
        private readonly Router $router,
        private readonly AuthenticatorInterface $authenticator,
        private readonly Authorizer $authorizer,
        private readonly ContainerInterface $container,
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $matched = $this->router->match($request);
            /** @var Route $route */
            $route = $matched['route'];
            $request = $request->withAttributes($matched['params']);

            $user = $this->authenticator->authenticate($request, $route->authRequired());
            $request = $request->withUser($user);

            $this->authorizer->authorize($route);

            return $this->invoke($route->action(), $request);
        } catch (ApiException $e) {
            return Response::error([
                'message' => $e->getMessage(),
                'code' => $e->codeKey(),
            ], $e->status());
        }
    }

    private function invoke(mixed $action, Request $request): Response
    {
        if (is_callable($action) && ! is_array($action)) {
            /** @var Response $response */
            $response = $action($request);

            return $response;
        }

        if (is_array($action) && count($action) === 2) {
            [$class, $method] = $action;
            $controller = $this->container->has($class)
                ? $this->container->get($class)
                : new $class();

            /** @var Response $response */
            $response = $controller->{$method}($request);

            return $response;
        }

        throw new ApiException('Invalid route action.', 500, 'server_error');
    }
}
