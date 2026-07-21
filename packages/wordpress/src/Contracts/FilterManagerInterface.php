<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Contracts;

/**
 * WordPress filter hook registration contract.
 */
interface FilterManagerInterface
{
    public function addFilter(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): void;

    public function removeFilter(string $hook, callable $callback, int $priority = 10): bool;

    public function applyFilters(string $hook, mixed $value, mixed ...$args): mixed;
}
