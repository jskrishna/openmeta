<?php

declare(strict_types=1);

namespace OpenMeta\Support\Traits;

/**
 * Optional when/unless fluent branching. Prefer composition over stacking many traits.
 */
trait Conditionable
{
    /**
     * @param callable(static): mixed $callback
     * @param callable(static): mixed|null $default
     */
    public function when(bool $condition, callable $callback, ?callable $default = null): mixed
    {
        if ($condition) {
            return $callback($this) ?? $this;
        }

        if ($default !== null) {
            return $default($this) ?? $this;
        }

        return $this;
    }

    /**
     * @param callable(static): mixed $callback
     * @param callable(static): mixed|null $default
     */
    public function unless(bool $condition, callable $callback, ?callable $default = null): mixed
    {
        return $this->when(! $condition, $callback, $default);
    }
}
