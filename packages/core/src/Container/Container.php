<?php

declare(strict_types=1);

namespace OpenMeta\Core\Container;

use Closure;
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Exceptions\BindingResolutionException;

/**
 * Dependency injection container — framework heart.
 *
 * Supported: bind, singleton, resolve, aliases.
 * Planned (not implemented): auto-resolution, deferred services.
 */
final class Container implements ContainerInterface
{
    /** @var array<string, mixed> */
    private array $bindings = [];

    /** @var array<string, true> */
    private array $shared = [];

    /** @var array<string, object> */
    private array $instances = [];

    /** @var array<string, string> alias => abstract */
    private array $aliases = [];

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

    public function alias(string $abstract, string $alias): void
    {
        if ($abstract === $alias) {
            throw new BindingResolutionException(
                sprintf('Cannot alias [%s] to itself.', $abstract)
            );
        }

        $this->aliases[$alias] = $abstract;
    }

    public function has(string $id): bool
    {
        $id = $this->getAlias($id);

        return isset($this->bindings[$id]) || isset($this->instances[$id]);
    }

    public function get(string $id): mixed
    {
        return $this->resolve($id);
    }

    public function resolve(string $id): mixed
    {
        $id = $this->getAlias($id);

        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (! isset($this->bindings[$id])) {
            throw new BindingResolutionException(
                sprintf('Service [%s] is not bound in the container.', $id)
            );
        }

        $concrete = $this->bindings[$id];
        $resolved = $this->build($concrete, $id);

        if (isset($this->shared[$id]) && is_object($resolved)) {
            $this->instances[$id] = $resolved;
        }

        return $resolved;
    }

    /**
     * Follow alias chain to the concrete abstract id.
     *
     * @throws BindingResolutionException On circular aliases
     */
    private function getAlias(string $id): string
    {
        if (! isset($this->aliases[$id])) {
            return $id;
        }

        $seen = [];

        while (isset($this->aliases[$id])) {
            if (isset($seen[$id])) {
                throw new BindingResolutionException(
                    sprintf('Circular alias detected for [%s].', $id)
                );
            }

            $seen[$id] = true;
            $id = $this->aliases[$id];
        }

        return $id;
    }

    /**
     * Build a concrete binding value.
     *
     * Auto-resolution of constructor dependencies is intentionally deferred.
     */
    private function build(mixed $concrete, string $resolvingId): mixed
    {
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }

        if (is_object($concrete)) {
            return $concrete;
        }

        if (is_string($concrete)) {
            if ($concrete !== $resolvingId && $this->isBoundTarget($concrete)) {
                return $this->resolve($concrete);
            }

            if (class_exists($concrete)) {
                // Future: auto-resolution via reflection / constructor injection.
                return new $concrete();
            }
        }

        throw new BindingResolutionException(
            sprintf('Cannot resolve binding for [%s].', $resolvingId)
        );
    }

    private function isBoundTarget(string $id): bool
    {
        $id = $this->getAlias($id);

        return isset($this->bindings[$id]) || isset($this->instances[$id]);
    }
}
