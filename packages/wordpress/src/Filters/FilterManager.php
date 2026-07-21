<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Filters;

use OpenMeta\Wordpress\Contracts\FilterManagerInterface;
use OpenMeta\Wordpress\Runtime\WordPressRuntimeInterface;

/**
 * Thin filter hook manager over the WordPress runtime.
 */
final class FilterManager implements FilterManagerInterface
{
    public function __construct(private readonly WordPressRuntimeInterface $runtime)
    {
    }

    public function addFilter(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        $this->runtime->addFilter($hook, $callback, $priority, $acceptedArgs);
    }

    public function removeFilter(string $hook, callable $callback, int $priority = 10): bool
    {
        return $this->runtime->removeFilter($hook, $callback, $priority);
    }

    public function applyFilters(string $hook, mixed $value, mixed ...$args): mixed
    {
        return $this->runtime->applyFilters($hook, $value, ...$args);
    }
}
