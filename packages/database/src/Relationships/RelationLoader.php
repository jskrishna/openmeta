<?php

declare(strict_types=1);

namespace OpenMeta\Database\Relationships;

use OpenMeta\Database\Connections\MemoryConnection;
use OpenMeta\Database\Contracts\ConnectionInterface;

/**
 * Batch loaders for relations — limits N+1 when used at the API boundary.
 */
final class RelationLoader
{
    public function __construct(private readonly ConnectionInterface $connection)
    {
    }

    /**
     * @param list<array<string, mixed>> $parents
     * @return array<string|int, list<array<string, mixed>>>
     */
    public function hasMany(
        array $parents,
        string $relatedTable,
        string $foreignKey,
        string $localKey = 'id',
    ): array {
        $ids = $this->collectKeys($parents, $localKey);

        if ($ids === []) {
            return [];
        }

        $rows = $this->whereIn($relatedTable, $foreignKey, $ids);
        $grouped = [];

        foreach ($rows as $row) {
            $key = $row[$foreignKey] ?? null;
            if ($key === null) {
                continue;
            }
            $grouped[$key][] = $row;
        }

        return $grouped;
    }

    /**
     * @param list<array<string, mixed>> $children
     * @return array<string|int, array<string, mixed>>
     */
    public function belongsTo(
        array $children,
        string $parentTable,
        string $foreignKey,
        string $ownerKey = 'id',
    ): array {
        $ids = $this->collectKeys($children, $foreignKey);

        if ($ids === []) {
            return [];
        }

        $rows = $this->whereIn($parentTable, $ownerKey, $ids);
        $keyed = [];

        foreach ($rows as $row) {
            $key = $row[$ownerKey] ?? null;
            if ($key === null) {
                continue;
            }
            $keyed[$key] = $row;
        }

        return $keyed;
    }

    public function belongsToMany(
        array $parents,
        string $pivotTable,
        string $relatedTable,
        string $foreignPivotKey,
        string $relatedPivotKey,
        string $parentKey = 'id',
        string $relatedKey = 'id',
    ): array {
        $ids = $this->collectKeys($parents, $parentKey);

        if ($ids === []) {
            return [];
        }

        $pivots = $this->whereIn($pivotTable, $foreignPivotKey, $ids);
        $relatedIds = $this->collectKeys($pivots, $relatedPivotKey);
        $related = $this->whereIn($relatedTable, $relatedKey, $relatedIds);
        $relatedById = [];

        foreach ($related as $row) {
            $key = $row[$relatedKey] ?? null;
            if ($key !== null) {
                $relatedById[$key] = $row;
            }
        }

        $grouped = [];
        foreach ($pivots as $pivot) {
            $parentId = $pivot[$foreignPivotKey] ?? null;
            $relatedId = $pivot[$relatedPivotKey] ?? null;
            if ($parentId === null || $relatedId === null || ! isset($relatedById[$relatedId])) {
                continue;
            }
            $grouped[$parentId][] = $relatedById[$relatedId];
        }

        return $grouped;
    }

    /**
     * Alias for hasMany (one-to-many infrastructure).
     *
     * @param list<array<string, mixed>> $parents
     * @return array<string|int, list<array<string, mixed>>>
     */
    public function oneToMany(
        array $parents,
        string $relatedTable,
        string $foreignKey,
        string $localKey = 'id',
    ): array {
        return $this->hasMany($parents, $relatedTable, $foreignKey, $localKey);
    }

    /**
     * @param list<array<string, mixed>> $rows
     * @return list<int|string>
     */
    private function collectKeys(array $rows, string $key): array
    {
        $ids = [];

        foreach ($rows as $row) {
            if (! array_key_exists($key, $row) || $row[$key] === null) {
                continue;
            }
            $ids[] = $row[$key];
        }

        /** @var list<int|string> */
        return array_values(array_unique($ids, SORT_REGULAR));
    }

    /**
     * @param list<int|string> $ids
     * @return list<array<string, mixed>>
     */
    private function whereIn(string $table, string $column, array $ids): array
    {
        if ($this->connection instanceof MemoryConnection) {
            return $this->connection->queryWhereIn(
                $this->connection->table($table),
                $column,
                $ids
            );
        }

        $placeholders = implode(', ', array_fill(0, count($ids), '?'));
        $tableName = $this->quote($this->connection->table($table));
        $columnName = $this->quote($column);
        $sql = sprintf('SELECT * FROM %s WHERE %s IN (%s)', $tableName, $columnName, $placeholders);

        return $this->connection->select($sql, $ids);
    }

    private function quote(string $name): string
    {
        if ($this->connection->driver() === 'sqlite') {
            return '"' . str_replace('"', '""', $name) . '"';
        }

        return '`' . str_replace('`', '``', $name) . '`';
    }
}
