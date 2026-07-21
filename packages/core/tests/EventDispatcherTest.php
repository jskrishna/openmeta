<?php

declare(strict_types=1);

namespace OpenMeta\Core\Tests;

use OpenMeta\Core\Events\EventDispatcher;
use OpenMeta\Core\Events\FrameworkBooted;

final class EventDispatcherTest extends CoreTestCase
{
    public function test_listen_and_dispatch_invokes_listeners(): void
    {
        $dispatcher = new EventDispatcher();
        $seen = [];

        $dispatcher->listen(FrameworkBooted::class, static function (object $event) use (&$seen): void {
            self::assertInstanceOf(FrameworkBooted::class, $event);
            $seen[] = $event->version;
        });

        $returned = $dispatcher->dispatch(new FrameworkBooted('0.1.0-alpha'));

        self::assertInstanceOf(FrameworkBooted::class, $returned);
        self::assertSame(['0.1.0-alpha'], $seen);
    }

    public function test_dispatch_without_listeners_returns_event(): void
    {
        $dispatcher = new EventDispatcher();
        $event = new FrameworkBooted('test');

        self::assertSame($event, $dispatcher->dispatch($event));
    }

    public function test_multiple_listeners_run_in_order(): void
    {
        $dispatcher = new EventDispatcher();
        $order = [];

        $dispatcher->listen(FrameworkBooted::class, static function () use (&$order): void {
            $order[] = 'a';
        });
        $dispatcher->listen(FrameworkBooted::class, static function () use (&$order): void {
            $order[] = 'b';
        });

        $dispatcher->dispatch(new FrameworkBooted('x'));

        self::assertSame(['a', 'b'], $order);
    }
}
