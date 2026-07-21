<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Hooks;

use OpenMeta\Wordpress\Contracts\HookManagerInterface;
use OpenMeta\Wordpress\Runtime\WordPressRuntimeInterface;

/**
 * Thin action hook manager over the WordPress runtime.
 */
final class HookManager implements HookManagerInterface
{
    public function __construct(private readonly WordPressRuntimeInterface $runtime)
    {
    }

    public function addAction(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        $this->runtime->addAction($hook, $callback, $priority, $acceptedArgs);
    }

    public function removeAction(string $hook, callable $callback, int $priority = 10): bool
    {
        return $this->runtime->removeAction($hook, $callback, $priority);
    }

    public function doAction(string $hook, mixed ...$args): void
    {
        $this->runtime->doAction($hook, ...$args);
    }
}
