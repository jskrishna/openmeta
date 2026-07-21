<?php

declare(strict_types=1);

namespace OpenMeta\Database\Collections;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * Immutable result set wrapper for query rows.
 *
 * @implements IteratorAggregate<int, array<string, mixed>>
 */
final class ResultCollection implements Countable, IteratorAggregate
{
    /** @param list<array<string, mixed>> $items */
    public function __construct(private readonly array $items = [])
    {
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function all(): array
    {
        return $this->items;
    }

    public function first(): ?array
    {
        return $this->items[0] ?? null;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    /** @return ArrayIterator<int, array<string, mixed>> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
