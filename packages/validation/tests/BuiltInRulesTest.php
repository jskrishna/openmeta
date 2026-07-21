<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Tests;

use OpenMeta\Support\Uuid\Uuid;
use OpenMeta\Validation\Tests\Fixtures\Status;
use OpenMeta\Validation\Validation;

final class BuiltInRulesTest extends ValidationTestCase
{
    public function test_required_nullable_string_numeric_boolean_array(): void
    {
        self::assertTrue(Validation::make(['n' => 'x'], ['n' => 'required'])->passes());
        self::assertTrue(Validation::make(['n' => ''], ['n' => 'required'])->fails());
        self::assertTrue(Validation::make(['n' => null], ['n' => 'nullable|string'])->passes());
        self::assertTrue(Validation::make(['n' => 'ok'], ['n' => 'string'])->passes());
        self::assertTrue(Validation::make(['n' => 1], ['n' => 'string'])->fails());
        self::assertTrue(Validation::make(['n' => '12.5'], ['n' => 'numeric'])->passes());
        self::assertTrue(Validation::make(['n' => true], ['n' => 'boolean'])->passes());
        self::assertTrue(Validation::make(['n' => ['a']], ['n' => 'array'])->passes());
        self::assertTrue(Validation::make(['n' => 7], ['n' => 'integer|min:5|max:10'])->passes());
        self::assertTrue(Validation::make(['n' => 3], ['n' => 'integer|min:5'])->fails());
        self::assertTrue(Validation::make(['n' => 'a'], ['n' => 'in:a,b'])->passes());
        self::assertTrue(Validation::make(['n' => 'c'], ['n' => 'in:a,b'])->fails());
        self::assertTrue(Validation::make(['n' => 'not-an-email'], ['n' => 'email'])->fails());
        self::assertTrue(Validation::make(['n' => 'dev@openmeta.test'], ['n' => 'email'])->passes());
    }

    public function test_type_and_format_rules(): void
    {
        self::assertTrue(Validation::make(['n' => 1.5], ['n' => 'float'])->passes());
        self::assertTrue(Validation::make(['u' => 'https://openmeta.test'], ['u' => 'url'])->passes());
        self::assertTrue(Validation::make(['id' => Uuid::v4()], ['id' => 'uuid'])->passes());
        self::assertTrue(Validation::make(['d' => '2024-01-15'], ['d' => 'date'])->passes());
        self::assertTrue(Validation::make(['t' => '2024-01-15 12:30:00'], ['t' => 'datetime:Y-m-d H:i:s'])->passes());
        self::assertTrue(Validation::make(['s' => Status::Active->value], ['s' => 'enum:' . Status::class])->passes());
        self::assertTrue(Validation::make(['o' => (object) ['a' => 1]], ['o' => 'object'])->passes());
    }

    public function test_comparison_and_string_rules(): void
    {
        self::assertTrue(Validation::make(['age' => 25], ['age' => 'between:18,30'])->passes());
        self::assertTrue(Validation::make(['age' => 10], ['age' => 'between:18,30'])->fails());
        self::assertTrue(Validation::make(['code' => 'abcd'], ['code' => 'length:4'])->passes());
        self::assertTrue(Validation::make(['code' => 'abc'], ['code' => 'regex:/^[a-z]+$/'])->passes());
        self::assertTrue(Validation::make(['name' => 'OpenMeta'], ['name' => 'starts_with:Open'])->passes());
        self::assertTrue(Validation::make(['name' => 'OpenMeta'], ['name' => 'ends_with:Meta'])->passes());
        self::assertTrue(Validation::make(['name' => 'OpenMeta'], ['name' => 'contains:Meta'])->passes());
        self::assertTrue(Validation::make(['role' => 'guest'], ['role' => 'not_in:admin,root'])->passes());
        self::assertTrue(Validation::make(['role' => 'admin'], ['role' => 'not_in:admin,root'])->fails());
    }

    public function test_failing_type_rules(): void
    {
        self::assertTrue(Validation::make(['u' => 'not-a-url'], ['u' => 'url'])->fails());
        self::assertTrue(Validation::make(['id' => 'nope'], ['id' => 'uuid'])->fails());
        self::assertTrue(Validation::make(['d' => '15-01-2024'], ['d' => 'date'])->fails());
        self::assertTrue(Validation::make(['s' => 'pending'], ['s' => 'enum:' . Status::class])->fails());
        self::assertTrue(Validation::make(['o' => ['a' => 1]], ['o' => 'object'])->fails());
    }
}
