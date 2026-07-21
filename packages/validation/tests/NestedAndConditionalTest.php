<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Tests;

use OpenMeta\Validation\Context\ValidationContext;
use OpenMeta\Validation\Validation;

final class NestedAndConditionalTest extends ValidationTestCase
{
    public function test_nested_dot_notation(): void
    {
        $validator = Validation::make(
            [
                'user' => [
                    'profile' => [
                        'email' => 'dev@openmeta.test',
                    ],
                ],
            ],
            [
                'user.profile.email' => 'required|email',
            ]
        );

        self::assertTrue($validator->passes());

        self::assertTrue(
            Validation::make(
                ['user' => ['profile' => ['email' => 'bad']]],
                ['user.profile.email' => 'required|email']
            )->fails()
        );
    }

    public function test_required_if_conditional(): void
    {
        self::assertTrue(
            Validation::make(
                ['type' => 'guest', 'company' => null],
                ['company' => 'required_if:type,org']
            )->passes()
        );

        self::assertTrue(
            Validation::make(
                ['type' => 'org', 'company' => ''],
                ['company' => 'required_if:type,org']
            )->fails()
        );

        self::assertTrue(
            Validation::make(
                ['type' => 'org', 'company' => 'Acme'],
                ['company' => 'required_if:type,org|string']
            )->passes()
        );
    }

    public function test_from_context_and_result(): void
    {
        $context = new ValidationContext(
            ['email' => 'bad'],
            ['email' => 'required|email'],
        );

        $result = Validation::fromContext($context)->result();

        self::assertTrue($result->failed());
        self::assertTrue($result->errors()->has('email'));
        self::assertSame(['email' => 'bad'], $result->data());
    }
}
