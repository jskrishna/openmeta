<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Routes;

/**
 * Applies shared prefix, middleware, and name prefix to routes.
 */
final class RouteGroup
{
    /**
     * @param list<class-string|object> $middleware
     */
    public function __construct(
        private readonly string $prefix = '',
        private readonly array $middleware = [],
        private readonly string $namePrefix = '',
    ) {
    }

    public function prefix(): string
    {
        return $this->prefix;
    }

    /** @return list<class-string|object> */
    public function middleware(): array
    {
        return $this->middleware;
    }

    public function namePrefix(): string
    {
        return $this->namePrefix;
    }

    public function apply(Route $route): Route
    {
        $path = $this->joinPath($this->prefix, $route->path());
        $name = $route->name() !== null ? $this->namePrefix . $route->name() : null;

        return new Route(
            $route->method(),
            $path,
            $route->action(),
            $name,
            [...$this->middleware, ...$route->middleware()],
            $route->authRequired(),
            $route->permissions(),
        );
    }

    private function joinPath(string $prefix, string $path): string
    {
        $prefix = trim($prefix, '/');
        $path = trim($path, '/');

        if ($prefix === '') {
            return '/' . $path;
        }

        if ($path === '') {
            return '/' . $prefix;
        }

        return '/' . $prefix . '/' . $path;
    }
}
