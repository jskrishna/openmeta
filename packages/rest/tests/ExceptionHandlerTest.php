<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Tests;

use OpenMeta\Rest\Exceptions\ExceptionHandler;
use OpenMeta\Rest\Exceptions\NotFoundException;
use OpenMeta\Rest\Exceptions\ValidationException;
use OpenMeta\Validation\Results\ErrorBag;
use OpenMeta\Validation\Results\ValidationError;

final class ExceptionHandlerTest extends RestTestCase
{
    public function test_maps_rest_exception_to_error_response(): void
    {
        $handler = new ExceptionHandler();
        $response = $handler->handle(new NotFoundException('Missing item.'));

        self::assertSame(404, $response->status());
        self::assertSame('not_found', $response->toArray()['error']['code']);
        self::assertSame('Missing item.', $response->toArray()['error']['message']);
    }

    public function test_maps_validation_exception_with_error_bag(): void
    {
        $errors = ErrorBag::empty()->add(new ValidationError(
            'name',
            'required',
            'Name is required.',
            'validation.required',
            []
        ));
        $handler = new ExceptionHandler();
        $response = $handler->handle(new ValidationException('Validation failed.', $errors));

        self::assertSame(422, $response->status());
        self::assertSame('validation_error', $response->toArray()['error']['code']);
        self::assertSame(['Name is required.'], $response->toArray()['error']['details']['errors']['name']);
    }
}
