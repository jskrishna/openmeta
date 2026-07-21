<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Tests\Fixtures;

use OpenMeta\Core\Contracts\EventDispatcherInterface;

/**
 * Subscribes to SDK events and records them for assertions.
 */
final class EventRecorder
{
    /** @var list<object> */
    public array $events = [];

    /**
     * @param list<class-string> $eventClasses
     */
    public function listenTo(EventDispatcherInterface $dispatcher, array $eventClasses): void
    {
        foreach ($eventClasses as $eventClass) {
            $dispatcher->listen($eventClass, function (object $event): void {
                $this->events[] = $event;
            });
        }
    }

    /**
     * @param class-string $eventClass
     */
    public function count(string $eventClass): int
    {
        return count(array_filter($this->events, static fn (object $event): bool => $event instanceof $eventClass));
    }

    public function total(): int
    {
        return count($this->events);
    }
}
