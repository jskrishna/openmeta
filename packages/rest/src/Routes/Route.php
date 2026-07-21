<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Routes;

/**
 * One HTTP route definition.
 */
final class Route
{
    /**
     * @param callable|array{0: class-string, 1: string} $action
     * @param list<class-string|object> $middleware
     * @param list<string> $permissions
     */
    public function __construct(
        private readonly string $method,
        private readonly string $path,
        private readonly mixed $action,
        private readonly ?string $name = null,
        private readonly array $middleware = [],
        private readonly bool $authRequired = false,
        private readonly array $permissions = [],
    ) {
    }

    public function method(): string
    {
        return strtoupper($this->method);
    }

    public function path(): string
    {
        return $this->path;
    }

    public function action(): mixed
    {
        return $this->action;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    /** @return list<class-string|object> */
    public function middleware(): array
    {
        return $this->middleware;
    }

    public function authRequired(): bool
    {
        return $this->authRequired;
    }

    /** @return list<string> */
    public function permissions(): array
    {
        return $this->permissions;
    }

    /**
     * @return array<string, string>|null
     */
    public function match(string $method, string $path): ?array
    {
        if ($this->method() !== strtoupper($method)) {
            return null;
        }

        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $this->path);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $path, $matches) !== 1) {
            return null;
        }

        $params = [];

        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $params[$key] = $value;
            }
        }

        return $params;
    }

    /**
     * Path-only match (any HTTP method).
     *
     * @return array<string, string>|null
     */
    public function matchPath(string $path): ?array
    {
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $this->path);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $path, $matches) !== 1) {
            return null;
        }

        $params = [];

        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $params[$key] = $value;
            }
        }

        return $params;
    }

    /**
     * @param list<class-string|object> $middleware
     * @param list<string> $permissions
     */
    public function with(
        ?string $name = null,
        array $middleware = [],
        ?bool $authRequired = null,
        array $permissions = [],
    ): self {
        return new self(
            $this->method,
            $this->path,
            $this->action,
            $name ?? $this->name,
            $middleware !== [] ? $middleware : $this->middleware,
            $authRequired ?? $this->authRequired,
            $permissions !== [] ? $permissions : $this->permissions,
        );
    }
}
