<?php

declare(strict_types=1);

namespace OpenMeta\Rest;

use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Rest\Authentication\NullAuthenticator;
use OpenMeta\Rest\Authorization\GateAuthorizer;
use OpenMeta\Rest\Contracts\AuthenticatorInterface;
use OpenMeta\Rest\Contracts\ResourceInterface;
use OpenMeta\Rest\Contracts\RouterInterface;
use OpenMeta\Rest\Events\ControllerExecuted;
use OpenMeta\Rest\Events\ExceptionThrown;
use OpenMeta\Rest\Events\RequestReceived;
use OpenMeta\Rest\Events\ResponseGenerated;
use OpenMeta\Rest\Events\RouteMatched;
use OpenMeta\Rest\Exceptions\ExceptionHandler;
use OpenMeta\Rest\Exceptions\RestException;
use OpenMeta\Rest\Middleware\Authenticate;
use OpenMeta\Rest\Middleware\Authorize;
use OpenMeta\Rest\Middleware\MiddlewarePriority;
use OpenMeta\Rest\Middleware\Pipeline;
use OpenMeta\Rest\Requests\Request;
use OpenMeta\Rest\Responses\JsonResponse;
use OpenMeta\Rest\Responses\Response;
use OpenMeta\Rest\Routes\Route;
use Throwable;

/**
 * REST dispatch kernel — match, middleware, controller, response.
 */
final class RestKernel
{
    /**
     * @param list<class-string|object> $globalMiddleware
     */
    public function __construct(
        private readonly RouterInterface $router,
        private readonly Pipeline $pipeline,
        private readonly AuthenticatorInterface $authenticator,
        private readonly GateAuthorizer $authorizer,
        private readonly ExceptionHandler $exceptionHandler,
        private readonly EventDispatcherInterface $events,
        private readonly ContainerInterface $container,
        private readonly array $globalMiddleware = [],
    ) {
    }

    public function handle(Request $request): Response
    {
        $this->events->dispatch(new RequestReceived($request));

        try {
            $matched = $this->router->match($request);
            /** @var Route $route */
            $route = $matched['route'];
            $params = $matched['params'];
            $request = $request->withAttributes($params);

            $this->events->dispatch(new RouteMatched($request, $route, $params));

            $middleware = $this->buildMiddlewareStack($route);

            $response = $this->pipeline
                ->send($request)
                ->through($middleware)
                ->then(function (Request $req) use ($route): Response {
                    $response = $this->invoke($route->action(), $req);
                    $this->events->dispatch(new ControllerExecuted($req, $route, $response));

                    return $response;
                });

            $this->events->dispatch(new ResponseGenerated($request, $response));

            return $response;
        } catch (Throwable $throwable) {
            $this->events->dispatch(new ExceptionThrown($request, $throwable));

            return $this->exceptionHandler->handle($throwable);
        }
    }

    /** @return list<class-string|object> */
    private function buildMiddlewareStack(Route $route): array
    {
        $middleware = [...$this->globalMiddleware, ...$route->middleware()];

        if ($route->authRequired()) {
            $middleware[] = new Authenticate($this->authenticator, true);
        }

        if ($route->permissions() !== []) {
            $middleware[] = new Authorize($this->authorizer, $route->permissions());
        }

        return MiddlewarePriority::sort($middleware);
    }

    private function invoke(mixed $action, Request $request): Response
    {
        if (is_callable($action) && ! is_array($action)) {
            return $this->normalizeResponse($action($request));
        }

        if (is_array($action) && count($action) === 2) {
            [$class, $method] = $action;
            $controller = $this->container->has($class)
                ? $this->container->get($class)
                : new $class();

            return $this->normalizeResponse($controller->{$method}($request));
        }

        throw new RestException('Invalid route action.', 500, 'server_error');
    }

    private function normalizeResponse(mixed $result): Response
    {
        if ($result instanceof Response) {
            return $result;
        }

        if ($result instanceof ResourceInterface) {
            return JsonResponse::make($result->toArray());
        }

        if (is_array($result)) {
            return JsonResponse::make($result);
        }

        throw new RestException('Invalid controller response.', 500, 'server_error');
    }
}
