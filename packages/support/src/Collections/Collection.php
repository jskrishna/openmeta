<?php

declare(strict_types=1);

namespace OpenMeta\Support\Collections;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use OpenMeta\Support\Arr\Arr;
use OpenMeta\Support\Contracts\ArrayableInterface;
use Traversable;

/**
 * Immutable-by-default fluent collection. Mutating methods return a new instance.
 *
 * @implements IteratorAggregate<array-key, mixed>
 */
final class Collection implements ArrayableInterface, Countable, IteratorAggregate
{
    /** @param array<array-key, mixed> $items */
    private function __construct(private array $items)
    {
    }

    /**
     * @param array<array-key, mixed>|self|null $items
     */
    public static function make(array|self|null $items = []): self
    {
        if ($items instanceof self) {
            return new self($items->items);
        }

        return new self($items ?? []);
    }

    /** @return array<array-key, mixed> */
    public function all(): array
    {
        return $this->items;
    }

    /** @return array<array-key, mixed> */
    public function toArray(): array
    {
        return $this->items;
    }

    /** @return list<mixed> */
    public function values(): array
    {
        return array_values($this->items);
    }

    /** @return list<array-key> */
    public function keys(): array
    {
        return array_keys($this->items);
    }

    /**
     * @param callable(mixed, array-key): mixed $callback
     */
    public function map(callable $callback): self
    {
        $result = [];

        foreach ($this->items as $key => $value) {
            $result[$key] = $callback($value, $key);
        }

        return new self($result);
    }

    /**
     * @param callable(mixed, array-key): bool $callback
     */
    public function filter(?callable $callback = null): self
    {
        if ($callback === null) {
            return new self(array_filter($this->items));
        }

        $result = [];

        foreach ($this->items as $key => $value) {
            if ($callback($value, $key)) {
                $result[$key] = $value;
            }
        }

        return new self($result);
    }

    /**
     * @param callable(mixed, mixed, array-key): mixed $callback
     */
    public function reduce(callable $callback, mixed $initial = null): mixed
    {
        $carry = $initial;

        foreach ($this->items as $key => $value) {
            $carry = $callback($carry, $value, $key);
        }

        return $carry;
    }

    public function pluck(string|int $value, string|int|null $key = null): self
    {
        return new self(Arr::pluck($this->items, $value, $key));
    }

    /**
     * @param callable(mixed, array-key): array-key|string $groupBy
     */
    public function groupBy(callable|string $groupBy): self
    {
        $result = [];

        foreach ($this->items as $key => $value) {
            $groupKey = is_string($groupBy)
                ? (is_array($value) ? Arr::get($value, $groupBy) : null)
                : $groupBy($value, $key);

            if (! is_string($groupKey) && ! is_int($groupKey)) {
                $groupKey = (string) $groupKey;
            }

            $result[$groupKey][] = $value;
        }

        return new self($result);
    }

    public function first(mixed $default = null): mixed
    {
        return Arr::first($this->items, $default);
    }

    public function last(mixed $default = null): mixed
    {
        return Arr::last($this->items, $default);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    /**
     * @param callable(mixed, array-key): void $callback
     */
    public function each(callable $callback): self
    {
        foreach ($this->items as $key => $value) {
            $callback($value, $key);
        }

        return $this;
    }

    /** @return ArrayIterator<array-key, mixed> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
