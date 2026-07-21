<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Tests;

use OpenMeta\Rest\Events\ControllerExecuted;
use OpenMeta\Rest\Events\ExceptionThrown;
use OpenMeta\Rest\Events\RequestReceived;
use OpenMeta\Rest\Events\ResponseGenerated;
use OpenMeta\Rest\Events\RouteMatched;
use OpenMeta\Rest\Requests\Request;
use OpenMeta\Rest\Responses\JsonResponse;

final class EventsTest extends RestTestCase
{
    public function test_kernel_fires_lifecycle_events_on_happy_path(): void
    {
        $fired = [];

        $dispatcher = $this->app->get(\OpenMeta\Core\Contracts\EventDispatcherInterface::class);
        $dispatcher->listen(RequestReceived::class, static function () use (&$fired): void {
            $fired[] = 'received';
        });
        $dispatcher->listen(RouteMatched::class, static function () use (&$fired): void {
            $fired[] = 'matched';
        });
        $dispatcher->listen(ControllerExecuted::class, static function () use (&$fired): void {
            $fired[] = 'executed';
        });
        $dispatcher->listen(ResponseGenerated::class, static function () use (&$fired): void {
            $fired[] = 'generated';
        });

        $this->router->get('/health', static fn (): JsonResponse => JsonResponse::make(['status' => 'ok']));

        $this->kernel->handle(Request::create('GET', '/health'));

        self::assertSame(['received', 'matched', 'executed', 'generated'], $fired);
    }

    public function test_kernel_fires_exception_event_on_failure(): void
    {
        $fired = false;

        $dispatcher = $this->app->get(\OpenMeta\Core\Contracts\EventDispatcherInterface::class);
        $dispatcher->listen(ExceptionThrown::class, static function () use (&$fired): void {
            $fired = true;
        });

        $this->kernel->handle(Request::create('GET', '/missing'));

        self::assertTrue($fired);
    }
}
