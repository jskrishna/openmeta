<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Tests;

use OpenMeta\Validation\Results\ErrorBag;
use OpenMeta\Validation\Results\ValidationError;
use OpenMeta\Validation\Validation;

final class ErrorBagTest extends ValidationTestCase
{
    public function test_add_has_first_all_merge_are_immutable(): void
    {
        $empty = ErrorBag::empty();
        $bag = $empty->add(
            new ValidationError('email', 'required', 'The email field is required.', 'validation.required')
        );

        self::assertTrue($empty->isEmpty());
        self::assertNotSame($empty, $bag);
        self::assertTrue($bag->has());
        self::assertTrue($bag->has('email'));
        self::assertFalse($bag->has('name'));
        self::assertSame('The email field is required.', $bag->first());
        self::assertSame('The email field is required.', $bag->first('email'));
        self::assertSame(['email' => ['The email field is required.']], $bag->all());
        self::assertSame(1, $bag->count());

        $other = ErrorBag::empty()->add(
            new ValidationError('name', 'required', 'The name field is required.', 'validation.required')
        );
        $merged = $bag->merge($other);

        self::assertSame(1, $bag->count());
        self::assertSame(2, $merged->count());
        self::assertFalse($merged->isEmpty());
    }

    public function test_validation_result_exposes_immutable_snapshot(): void
    {
        $result = Validation::make(
            ['email' => 'bad'],
            ['email' => 'required|email']
        )->result();

        $errors = $result->errors();
        $again = $errors->add(
            new ValidationError('x', 'required', 'x', 'validation.required')
        );

        self::assertTrue($result->failed());
        self::assertSame(1, $errors->count());
        self::assertSame(2, $again->count());
        self::assertSame(1, $result->errors()->count());
    }
}
