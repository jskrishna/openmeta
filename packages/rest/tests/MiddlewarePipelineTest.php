<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Tests;

use OpenMeta\Rest\Middleware\Pipeline;
use OpenMeta\Rest\Requests\Request;
use OpenMeta\Rest\Responses\JsonResponse;
use OpenMeta\Rest\Responses\Response;
use OpenMeta\Rest\Tests\Fixtures\OrderTrace;
use OpenMeta\Rest\Tests\Fixtures\RecordingMiddleware;

final class MiddlewarePipelineTest extends RestTestCase
{
    public function test_middleware_runs_before_and_after_destination(): void
    {
        $trace = new OrderTrace();

        $pipeline = new Pipeline();

        $response = $pipeline
            ->send(Request::create('GET', '/test'))
            ->through([
                new RecordingMiddleware($trace, 'before', before: true),
                new RecordingMiddleware($trace, 'after', before: false),
            ])
            ->then(static function () use ($trace): Response {
                $trace->push('destination');

                return JsonResponse::make(['ok' => true]);
            });

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame(['before', 'destination', 'after'], $trace->all());
    }
}
