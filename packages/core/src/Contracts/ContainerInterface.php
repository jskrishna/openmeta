<?php

declare(strict_types=1);

namespace OpenMeta\Core\Contracts;

/**
 * Service container contract.
 *
 * Current: bind, singleton, resolve, aliases.
 * Future: auto-resolution, deferred services.
 */
interface ContainerInterface
{
    /**
     * Register a binding. When $shared is true, the resolved value is cached.
     */
    public function bind(string $id, mixed $concrete, bool $shared = false): void;

    /**
     * Register a shared binding (one instance per container).
     */
    public function singleton(string $id, mixed $concrete): void;

    /**
     * Register an already-built object as the canonical instance for $id.
     */
    public function instance(string $id, object $instance): void;

    /**
     * Register $alias as an alternate id for $abstract.
     *
     * Resolving $alias returns the same service as resolving $abstract.
     */
    public function alias(string $abstract, string $alias): void;

    public function has(string $id): bool;

    /**
     * Resolve a binding by id (alias-aware). Same as {@see resolve()}.
     */
    public function get(string $id): mixed;

    /**
     * Resolve a binding by id (alias-aware).
     */
    public function resolve(string $id): mixed;
}
