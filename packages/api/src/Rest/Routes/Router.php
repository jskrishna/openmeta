<?php

declare(strict_types=1);

namespace OpenMeta\Api\Rest\Routes;

use OpenMeta\Api\Exceptions\NotFoundException;
use OpenMeta\Api\Rest\Request;

/**
 * Versioned REST route table (`openmeta/v1`).
 */
final class Router
{
    public const NAMESPACE = 'openmeta/v1';

    /** @var list<Route> */
    private array $routes = [];

    public function add(Route $route): void
    {
        $this->routes[] = $route;
    }

    /**
     * @param callable|array{0: class-string, 1: string}|string $action
     * @param list<string> $permissions
     */
    public function get(string $path, mixed $action, bool $auth = true, array $permissions = []): void
    {
        $this->add(new Route('GET', $path, $action, $auth, $permissions));
    }

    /**
     * @param callable|array{0: class-string, 1: string}|string $action
     * @param list<string> $permissions
     */
    public function put(string $path, mixed $action, bool $auth = true, array $permissions = []): void
    {
        $this->add(new Route('PUT', $path, $action, $auth, $permissions));
    }

    /**
     * @param callable|array{0: class-string, 1: string}|string $action
     * @param list<string> $permissions
     */
    public function post(string $path, mixed $action, bool $auth = true, array $permissions = []): void
    {
        $this->add(new Route('POST', $path, $action, $auth, $permissions));
    }

    /**
     * @param callable|array{0: class-string, 1: string}|string $action
     * @param list<string> $permissions
     */
    public function delete(string $path, mixed $action, bool $auth = true, array $permissions = []): void
    {
        $this->add(new Route('DELETE', $path, $action, $auth, $permissions));
    }

    /**
     * @return array{route: Route, params: array<string, string>}
     */
    public function match(Request $request): array
    {
        $path = $this->normalize($request->path());

        foreach ($this->routes as $route) {
            $params = $route->match($request->method(), $path);
            if ($params !== null) {
                return ['route' => $route, 'params' => $params];
            }
        }

        throw new NotFoundException(sprintf('No route matches [%s %s].', $request->method(), $path));
    }

    /** @return list<Route> */
    public function routes(): array
    {
        return $this->routes;
    }

    private function normalize(string $path): string
    {
        $path = '/' . trim($path, '/');
        $prefix = '/' . trim(self::NAMESPACE, '/');

        if (str_starts_with($path, $prefix)) {
            $path = substr($path, strlen($prefix));
        }

        if ($path === '') {
            return '/';
        }

        return str_starts_with($path, '/') ? $path : '/' . $path;
    }
}
