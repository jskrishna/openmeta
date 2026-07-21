<?php

declare(strict_types=1);

namespace OpenMeta\Core\Contracts;

/**
 * Synchronous event dispatcher contract.
 */
interface EventDispatcherInterface
{
    /**
     * @param callable(object): void $listener
     */
    public function listen(string $event, callable $listener): void;

    public function dispatch(object $event): object;
}
