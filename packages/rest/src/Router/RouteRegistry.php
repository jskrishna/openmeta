<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Router;

use OpenMeta\Rest\Routes\Route;

/**
 * DI-friendly façade over {@see Router}.
 */
final class RouteRegistry
{
    public function __construct(private readonly Router $router)
    {
    }

    public function router(): Router
    {
        return $this->router;
    }

    /**
     * @param callable|array{0: class-string, 1: string} $action
     * @param list<string> $permissions
     * @param list<class-string|object> $middleware
     */
    public function get(
        string $path,
        mixed $action,
        ?string $name = null,
        bool $authRequired = false,
        array $permissions = [],
        array $middleware = [],
    ): Route {
        return $this->router->get($path, $action, $name, $authRequired, $permissions, $middleware);
    }

    /**
     * @param callable|array{0: class-string, 1: string} $action
     * @param list<string> $permissions
     * @param list<class-string|object> $middleware
     */
    public function post(
        string $path,
        mixed $action,
        ?string $name = null,
        bool $authRequired = false,
        array $permissions = [],
        array $middleware = [],
    ): Route {
        return $this->router->post($path, $action, $name, $authRequired, $permissions, $middleware);
    }

    /**
     * @param array{
     *     prefix?: string,
     *     middleware?: list<class-string|object>,
     *     name?: string,
     *     version?: string
     * }|callable(): void $attributes
     */
    public function group(array|callable $attributes, ?callable $callback = null): void
    {
        $this->router->group($attributes, $callback);
    }

    public function add(Route $route): void
    {
        $this->router->add($route);
    }
}
