<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Contracts;

/**
 * WordPress action hook registration contract.
 */
interface HookManagerInterface
{
    public function addAction(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): void;

    public function removeAction(string $hook, callable $callback, int $priority = 10): bool;

    public function doAction(string $hook, mixed ...$args): void;
}
