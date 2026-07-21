<?php

declare(strict_types=1);

namespace OpenMeta\Api\Rest\Routes;

/**
 * One REST route under the OpenMeta namespace.
 */
final class Route
{
    /**
     * @param callable|array{0: class-string, 1: string}|string $action
     * @param list<string> $permissions OpenMeta permission ids (any grants access when non-empty)
     */
    public function __construct(
        private readonly string $method,
        private readonly string $path,
        private readonly mixed $action,
        private readonly bool $authRequired = true,
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
}
