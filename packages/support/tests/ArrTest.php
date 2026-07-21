<?php

declare(strict_types=1);

namespace OpenMeta\Support\Tests;

use OpenMeta\Support\Arr\Arr;

final class ArrTest extends SupportTestCase
{
    public function test_get_set_has_dot_notation(): void
    {
        $data = ['app' => ['name' => 'OpenMeta']];

        self::assertSame('OpenMeta', Arr::get($data, 'app.name'));
        self::assertTrue(Arr::has($data, 'app.name'));
        self::assertFalse(Arr::has($data, 'app.missing'));

        Arr::set($data, 'app.env', 'testing');
        self::assertSame('testing', Arr::get($data, 'app.env'));
    }

    public function test_only_except_wrap_flatten_pluck(): void
    {
        $row = ['a' => 1, 'b' => 2, 'c' => 3];

        self::assertSame(['a' => 1], Arr::only($row, ['a']));
        self::assertSame(['b' => 2, 'c' => 3], Arr::except($row, ['a']));
        self::assertSame([1], Arr::wrap(1));
        self::assertSame([], Arr::wrap(null));
        self::assertSame([1, 2, 3], Arr::flatten([[1, 2], [3]]));

        $items = [
            ['id' => 1, 'name' => 'a'],
            ['id' => 2, 'name' => 'b'],
        ];
        self::assertSame(['a', 'b'], Arr::pluck($items, 'name'));
        self::assertSame([1 => 'a', 2 => 'b'], Arr::pluck($items, 'name', 'id'));
    }

    public function test_first_last_is_assoc(): void
    {
        self::assertSame(1, Arr::first([1, 2]));
        self::assertSame(2, Arr::last([1, 2]));
        self::assertTrue(Arr::isAssoc(['a' => 1]));
        self::assertFalse(Arr::isAssoc([1, 2]));
    }
}
