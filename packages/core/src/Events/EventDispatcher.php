<?php

declare(strict_types=1);

namespace OpenMeta\Core\Events;

/**
 * Simple synchronous event dispatcher.
 */
final class EventDispatcher implements EventDispatcherInterface
{
    /** @var array<string, list<callable(object): void>> */
    private array $listeners = [];

    public function listen(string $event, callable $listener): void
    {
        $this->listeners[$event][] = $listener;
    }

    public function dispatch(object $event): object
    {
        $name = $event::class;

        foreach ($this->listeners[$name] ?? [] as $listener) {
            $listener($event);
        }

        return $event;
    }
}
