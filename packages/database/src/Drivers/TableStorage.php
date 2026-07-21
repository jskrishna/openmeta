<?php

declare(strict_types=1);

namespace OpenMeta\Database\Drivers;

use OpenMeta\Database\Contracts\ConnectionInterface;
use OpenMeta\Database\Query\QueryBuilder;

/**
 * Executes reads/writes for a single table via Connection + QueryBuilder.
 */
final class TableStorage
{
    public function __construct(
        private readonly ConnectionInterface $connection,
        private readonly string $table,
        private readonly string $keyName = 'id',
    ) {
    }

    public function table(): string
    {
        return $this->table;
    }

    public function keyName(): string
    {
        return $this->keyName;
    }

    public function query(): QueryBuilder
    {
        return (new QueryBuilder($this->connection))->from($this->table);
    }

    /** @return array<string, mixed>|null */
    public function find(int|string $id): ?array
    {
        return $this->query()->where($this->keyName, $id)->first();
    }

    /** @return list<array<string, mixed>> */
    public function all(): array
    {
        return $this->query()->get();
    }

    /**
     * @param array<string, mixed> $attributes
     * @return array<string, mixed>
     */
    public function insert(array $attributes): array
    {
        $id = $this->query()->insert($attributes);
        $row = $this->find($id);

        return $row ?? [...$attributes, $this->keyName => $id];
    }

    /**
     * @param array<string, mixed> $attributes
     * @return array<string, mixed>|null
     */
    public function update(int|string $id, array $attributes): ?array
    {
        $this->query()->where($this->keyName, $id)->update($attributes);

        return $this->find($id);
    }

    public function delete(int|string $id): bool
    {
        return $this->query()->where($this->keyName, $id)->delete() > 0;
    }
}
