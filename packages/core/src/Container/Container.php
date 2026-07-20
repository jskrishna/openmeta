<?php

declare(strict_types=1);

namespace OpenMeta\Core\Container;

use Closure;
use RuntimeException;

/**
 * Minimal service container for the core spine.
 */
final class Container
{
    /** @var array<string, mixed> */
    private array $bindings = [];

    /** @var array<string, true> */
    private array $shared = [];

    /** @var array<string, object> */
    private array $instances = [];

    public function bind(string $id, mixed $concrete, bool $shared = false): void
    {
        unset($this->instances[$id]);
        $this->bindings[$id] = $concrete;

        if ($shared) {
            $this->shared[$id] = true;
        } else {
            unset($this->shared[$id]);
        }
    }

    public function singleton(string $id, mixed $concrete): void
    {
        $this->bind($id, $concrete, true);
    }

    public function instance(string $id, object $instance): void
    {
        $this->shared[$id] = true;
        $this->instances[$id] = $instance;
        $this->bindings[$id] = $instance;
    }

    public function has(string $id): bool
    {
        return isset($this->bindings[$id]) || isset($this->instances[$id]);
    }

    public function get(string $id): mixed
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (! isset($this->bindings[$id])) {
            throw new RuntimeException(sprintf('Service [%s] is not bound in the container.', $id));
        }

        $concrete = $this->bindings[$id];
        $resolved = $this->resolve($concrete);

        if (isset($this->shared[$id]) && is_object($resolved)) {
            $this->instances[$id] = $resolved;
        }

        return $resolved;
    }

    private function resolve(mixed $concrete): mixed
    {
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }

        if (is_object($concrete)) {
            return $concrete;
        }

        if (is_string($concrete) && class_exists($concrete)) {
            return new $concrete();
        }

        throw new RuntimeException('Cannot resolve the given container binding.');
    }
}
