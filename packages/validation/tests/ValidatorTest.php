<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Tests;

use OpenMeta\Validation\Exceptions\ValidationException;
use OpenMeta\Validation\Rules\Rule;
use OpenMeta\Validation\Validation;

final class ValidatorTest extends ValidationTestCase
{
    public function test_validates_arrays_successfully(): void
    {
        $data = [
            'email' => 'dev@openmeta.test',
            'age' => 21,
            'tags' => ['a', 'b'],
        ];

        $validated = Validation::validate($data, [
            'email' => 'required|email',
            'age' => 'required|integer|min:18',
            'tags' => 'required|array|min:1',
        ]);

        self::assertSame($data, $validated);
    }

    public function test_validates_objects(): void
    {
        $payload = (object) [
            'name' => 'OpenMeta',
            'active' => true,
        ];

        $validator = Validation::make($payload, [
            'name' => ['required', 'string', 'min:3'],
            'active' => 'boolean',
        ]);

        self::assertTrue($validator->passes());
        self::assertSame('OpenMeta', $validator->validate()['name']);
    }

    public function test_validates_object_with_public_properties(): void
    {
        $payload = new class {
            public string $title = 'Hello';
            public int $count = 2;
        };

        self::assertTrue(
            Validation::make($payload, [
                'title' => 'required|string',
                'count' => 'integer|min:1',
            ])->passes()
        );
    }

    public function test_collects_errors_and_throws_on_validate(): void
    {
        $validator = Validation::make(
            ['email' => 'bad', 'age' => 10],
            [
                'email' => 'required|email',
                'age' => 'integer|min:18',
            ]
        );

        self::assertTrue($validator->fails());
        self::assertTrue($validator->errors()->has('email'));
        self::assertTrue($validator->errors()->has('age'));
        self::assertStringContainsString('email', (string) $validator->errors()->first('email'));

        $this->expectException(ValidationException::class);
        $validator->validate();
    }

    public function test_nullable_skips_other_rules_when_empty(): void
    {
        self::assertTrue(
            Validation::make(['nickname' => null], [
                'nickname' => 'nullable|string|min:3',
            ])->passes()
        );
    }

    public function test_custom_rules_via_extend_and_rule_object(): void
    {
        Validation::extend(
            'odd',
            static fn (string $attribute, mixed $value): bool => is_numeric($value) && ((int) $value % 2 === 1),
            'The :attribute field must be odd.'
        );

        self::assertTrue(Validation::make(['n' => 3], ['n' => 'odd'])->passes());
        self::assertTrue(Validation::make(['n' => 2], ['n' => 'odd'])->fails());

        $rule = new class extends Rule {
            public function __construct()
            {
                parent::__construct('upper', 'The :attribute field must be uppercase.');
            }

            public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
            {
                return is_string($value) && $value === mb_strtoupper($value, 'UTF-8');
            }
        };

        self::assertTrue(Validation::make(['code' => 'ABC'], ['code' => [$rule]])->passes());
        self::assertTrue(Validation::make(['code' => 'Abc'], ['code' => [$rule]])->fails());
    }

    public function test_custom_messages(): void
    {
        $validator = Validation::make(
            ['email' => ''],
            ['email' => 'required'],
            ['email.required' => 'Please provide an email.']
        );

        self::assertSame('Please provide an email.', $validator->errors()->first('email'));
    }

    public function test_result_object_on_success(): void
    {
        $result = Validation::make(
            ['email' => 'dev@openmeta.test'],
            ['email' => 'required|email']
        )->result();

        self::assertTrue($result->passed());
        self::assertFalse($result->failed());
        self::assertSame('dev@openmeta.test', $result->data()['email']);
        self::assertTrue($result->errors()->isEmpty());
    }
}
