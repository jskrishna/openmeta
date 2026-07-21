<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Routes;

/**
 * In-memory route collection.
 */
final class RouteCollection
{
    /** @var list<Route> */
    private array $routes = [];

    /** @var array<string, Route> */
    private array $named = [];

    public function add(Route $route): void
    {
        $this->routes[] = $route;

        if ($route->name() !== null) {
            $this->named[$route->name()] = $route;
        }
    }

    /** @return list<Route> */
    public function all(): array
    {
        return $this->routes;
    }

    public function getByName(string $name): ?Route
    {
        return $this->named[$name] ?? null;
    }
}
