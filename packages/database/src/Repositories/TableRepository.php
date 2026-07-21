<?php

declare(strict_types=1);

namespace OpenMeta\Database\Repositories;

use OpenMeta\Database\Contracts\ConnectionInterface;
use OpenMeta\Database\Contracts\RepositoryInterface;
use OpenMeta\Database\Query\QueryBuilder;
use OpenMeta\Database\Drivers\TableStorage;

/**
 * Table-backed repository abstraction for consumers.
 */
class TableRepository implements RepositoryInterface
{
    private readonly TableStorage $storage;

    public function __construct(
        ConnectionInterface $connection,
        string $table,
        string $keyName = 'id',
    ) {
        $this->storage = new TableStorage($connection, $table, $keyName);
    }

    public function find(int|string $id): ?array
    {
        return $this->storage->find($id);
    }

    public function all(): array
    {
        return $this->storage->all();
    }

    public function create(array $attributes): array
    {
        return $this->storage->insert($attributes);
    }

    public function update(int|string $id, array $attributes): ?array
    {
        return $this->storage->update($id, $attributes);
    }

    public function delete(int|string $id): bool
    {
        return $this->storage->delete($id);
    }

    public function query(): QueryBuilder
    {
        return $this->storage->query();
    }
}
