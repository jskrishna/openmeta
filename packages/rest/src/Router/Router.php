<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Router;

use OpenMeta\Rest\Contracts\RouterInterface;
use OpenMeta\Rest\Exceptions\MethodNotAllowedException;
use OpenMeta\Rest\Exceptions\NotFoundException;
use OpenMeta\Rest\Requests\Request;
use OpenMeta\Rest\Routes\Route;
use OpenMeta\Rest\Routes\RouteCollection;
use OpenMeta\Rest\Routes\RouteGroup;
use OpenMeta\Rest\Support\HttpMethod;

/**
 * Framework-independent HTTP router with grouping, versioning, and naming.
 */
final class Router implements RouterInterface
{
    private RouteCollection $collection;

    private string $prefix = '';

    private string $version = '';

    private string $namePrefix = '';

    /** @var list<class-string|object> */
    private array $groupMiddleware = [];

    /** @var list<RouteGroup> */
    private array $groupStack = [];

    public function __construct(?RouteCollection $collection = null)
    {
        $this->collection = $collection ?? new RouteCollection();
    }

    public function prefix(string $prefix): self
    {
        $this->prefix = trim($prefix, '/');

        return $this;
    }

    public function version(string $version): self
    {
        $this->version = trim($version, '/');

        return $this;
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
        if (is_callable($attributes) && $callback === null) {
            $callback = $attributes;
            $attributes = [];
        }

        if (! is_callable($callback)) {
            return;
        }

        $groupPrefix = $attributes['prefix'] ?? '';
        $groupMiddleware = $attributes['middleware'] ?? [];
        $groupName = $attributes['name'] ?? '';

        if (isset($attributes['version']) && is_string($attributes['version'])) {
            $version = trim($attributes['version'], '/');
            $suffix = $groupPrefix !== '' ? '/' . trim($groupPrefix, '/') : '';
            $groupPrefix = $version . $suffix;
        }

        $this->groupStack[] = new RouteGroup(
            $this->joinPath($this->prefix, $groupPrefix),
            [...$this->groupMiddleware, ...$groupMiddleware],
            $this->namePrefix . $groupName,
        );

        $callback($this);

        array_pop($this->groupStack);
    }

    public function add(Route $route): void
    {
        foreach ($this->groupStack as $group) {
            $route = $group->apply($route);
        }

        $path = $route->path();
        $name = $route->name() !== null ? $this->namePrefix . $route->name() : null;

        $this->collection->add(new Route(
            $route->method(),
            $path,
            $route->action(),
            $name,
            [...$this->groupMiddleware, ...$route->middleware()],
            $route->authRequired(),
            $route->permissions(),
        ));
    }

    /**
     * @param iterable<Route> $routes
     */
    public function discover(iterable $routes): void
    {
        foreach ($routes as $route) {
            $this->add($route);
        }
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
        return $this->register(
            HttpMethod::Get->value,
            $path,
            $action,
            $name,
            $authRequired,
            $permissions,
            $middleware
        );
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
        return $this->register(
            HttpMethod::Post->value,
            $path,
            $action,
            $name,
            $authRequired,
            $permissions,
            $middleware
        );
    }

    /**
     * @param callable|array{0: class-string, 1: string} $action
     * @param list<string> $permissions
     * @param list<class-string|object> $middleware
     */
    public function put(
        string $path,
        mixed $action,
        ?string $name = null,
        bool $authRequired = false,
        array $permissions = [],
        array $middleware = [],
    ): Route {
        return $this->register(
            HttpMethod::Put->value,
            $path,
            $action,
            $name,
            $authRequired,
            $permissions,
            $middleware
        );
    }

    /**
     * @param callable|array{0: class-string, 1: string} $action
     * @param list<string> $permissions
     * @param list<class-string|object> $middleware
     */
    public function patch(
        string $path,
        mixed $action,
        ?string $name = null,
        bool $authRequired = false,
        array $permissions = [],
        array $middleware = [],
    ): Route {
        return $this->register(
            HttpMethod::Patch->value,
            $path,
            $action,
            $name,
            $authRequired,
            $permissions,
            $middleware
        );
    }

    /**
     * @param callable|array{0: class-string, 1: string} $action
     * @param list<string> $permissions
     * @param list<class-string|object> $middleware
     */
    public function delete(
        string $path,
        mixed $action,
        ?string $name = null,
        bool $authRequired = false,
        array $permissions = [],
        array $middleware = [],
    ): Route {
        return $this->register(
            HttpMethod::Delete->value,
            $path,
            $action,
            $name,
            $authRequired,
            $permissions,
            $middleware
        );
    }

    /**
     * @param callable|array{0: class-string, 1: string} $action
     * @param list<string> $permissions
     * @param list<class-string|object> $middleware
     */
    public function options(
        string $path,
        mixed $action,
        ?string $name = null,
        bool $authRequired = false,
        array $permissions = [],
        array $middleware = [],
    ): Route {
        return $this->register(
            HttpMethod::Options->value,
            $path,
            $action,
            $name,
            $authRequired,
            $permissions,
            $middleware
        );
    }

    /**
     * @return array{route: Route, params: array<string, string>}
     */
    public function match(Request $request): array
    {
        $path = $this->normalizePath($request->path());
        $method = $request->method();
        $allowedMethods = [];

        foreach ($this->collection->all() as $route) {
            $params = $route->matchPath($path);

            if ($params === null) {
                continue;
            }

            if ($route->method() !== $method) {
                $allowedMethods[] = $route->method();

                continue;
            }

            return ['route' => $route, 'params' => $params];
        }

        if ($allowedMethods !== []) {
            $allowedMethods = array_values(array_unique($allowedMethods));

            throw new MethodNotAllowedException(
                sprintf('Method [%s] not allowed for [%s].', $method, $path),
                $allowedMethods,
            );
        }

        throw new NotFoundException(sprintf('No route matches [%s %s].', $method, $path));
    }

    /** @return list<Route> */
    public function routes(): array
    {
        return $this->collection->all();
    }

    public function collection(): RouteCollection
    {
        return $this->collection;
    }

    public function getByName(string $name): ?Route
    {
        return $this->collection->getByName($name);
    }

    /**
     * @param callable|array{0: class-string, 1: string} $action
     * @param list<string> $permissions
     * @param list<class-string|object> $middleware
     */
    private function register(
        string $method,
        string $path,
        mixed $action,
        ?string $name,
        bool $authRequired,
        array $permissions,
        array $middleware,
    ): Route {
        $route = new Route(
            $method,
            $this->normalizeRoutePath($path),
            $action,
            $name,
            $middleware,
            $authRequired,
            $permissions
        );
        $this->add($route);

        return $route;
    }

    private function normalizeRoutePath(string $path): string
    {
        $path = trim($path, '/');

        return $path === '' ? '/' : '/' . $path;
    }

    private function normalizePath(string $path): string
    {
        $path = '/' . trim($path, '/');

        if ($this->version !== '') {
            $versionPrefix = '/' . trim($this->version, '/');

            if (str_starts_with($path, $versionPrefix)) {
                $path = substr($path, strlen($versionPrefix));

                if ($path === '') {
                    return '/';
                }
            }
        }

        return str_starts_with($path, '/') ? $path : '/' . $path;
    }

    private function joinPath(string ...$parts): string
    {
        $segments = [];

        foreach ($parts as $part) {
            foreach (explode('/', trim($part, '/')) as $segment) {
                if ($segment !== '') {
                    $segments[] = $segment;
                }
            }
        }

        return implode('/', $segments);
    }
}
