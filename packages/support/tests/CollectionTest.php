<?php

declare(strict_types=1);

namespace OpenMeta\Support\Tests;

use OpenMeta\Support\Collections\Collection;
use OpenMeta\Support\Contracts\ArrayableInterface;

final class CollectionTest extends SupportTestCase
{
    public function test_map_filter_reduce_pluck_group_by(): void
    {
        $collection = Collection::make([
            ['id' => 1, 'type' => 'a', 'n' => 10],
            ['id' => 2, 'type' => 'b', 'n' => 20],
            ['id' => 3, 'type' => 'a', 'n' => 30],
        ]);

        $mapped = $collection->map(static fn (array $row): int => $row['n']);
        self::assertSame([10, 20, 30], $mapped->values());

        $filtered = $collection->filter(static fn (array $row): bool => $row['type'] === 'a');
        self::assertSame(2, $filtered->count());

        $sum = $collection->reduce(static fn (int $carry, array $row): int => $carry + $row['n'], 0);
        self::assertSame(60, $sum);

        self::assertSame([10, 20, 30], $collection->pluck('n')->all());

        $grouped = $collection->groupBy('type');
        self::assertCount(2, $grouped->all()['a']);
        self::assertCount(1, $grouped->all()['b']);
    }

    public function test_immutable_map_returns_new_instance(): void
    {
        $original = Collection::make([1, 2]);
        $mapped = $original->map(static fn (int $n): int => $n * 2);

        self::assertNotSame($original, $mapped);
        self::assertSame([1, 2], $original->all());
        self::assertSame([2, 4], $mapped->all());
    }

    public function test_first_last_empty_iterable(): void
    {
        $c = Collection::make(['x' => 1, 'y' => 2]);
        self::assertSame(1, $c->first());
        self::assertSame(2, $c->last());
        self::assertFalse($c->isEmpty());
        self::assertSame(['x', 'y'], $c->keys());
        self::assertCount(2, iterator_to_array($c));
    }

    public function test_implements_arrayable(): void
    {
        $c = Collection::make(['a' => 1]);
        self::assertInstanceOf(ArrayableInterface::class, $c);
        self::assertSame(['a' => 1], $c->toArray());
        self::assertSame($c->all(), $c->toArray());
    }
}
