<?php

declare(strict_types=1);

namespace OpenMeta\Database\Connections;

use OpenMeta\Database\Contracts\ConnectionInterface;
use OpenMeta\Database\Exceptions\QueryException;

/**
 * In-memory connection for CI and environments without pdo_sqlite.
 * Prepared-statement style APIs still apply — values are never interpolated into identifiers.
 */
final class MemoryConnection implements ConnectionInterface
{
    /** @var array<string, list<array<string, mixed>>> */
    private array $tables = [];

    /** @var array<string, list<string>> */
    private array $columns = [];

    private string $lastInsertId = '0';

    private int $autoIncrement = 0;

    public function __construct(private readonly string $prefix = 'om_')
    {
    }

    public function driver(): string
    {
        return 'memory';
    }

    public function prefix(): string
    {
        return $this->prefix;
    }

    public function table(string $name): string
    {
        return $this->prefix . $name;
    }

    public function hasTable(string $table): bool
    {
        return isset($this->tables[$table]);
    }

    /**
     * @param list<string> $columns
     */
    public function createTable(string $table, array $columns): void
    {
        $this->tables[$table] = [];
        $this->columns[$table] = $columns;
    }

    public function dropTable(string $table): void
    {
        unset($this->tables[$table], $this->columns[$table]);
    }

    /**
     * @param list<array{column: string, operator: string, value: mixed}> $wheres
     * @param list<array{column: string, direction: string}> $orders
     * @return list<array<string, mixed>>
     */
    public function querySelect(
        string $table,
        array $wheres = [],
        array $orders = [],
        ?int $limit = null,
        ?int $offset = null,
    ): array {
        $rows = $this->filter($table, $wheres);

        foreach ($orders as $order) {
            $column = $order['column'];
            $direction = $order['direction'];
            usort(
                $rows,
                static function (array $a, array $b) use ($column, $direction): int {
                    $left = $a[$column] ?? null;
                    $right = $b[$column] ?? null;
                    $cmp = $left <=> $right;

                    return $direction === 'DESC' ? -$cmp : $cmp;
                }
            );
        }

        if ($offset !== null) {
            $rows = array_slice($rows, $offset);
        }

        if ($limit !== null) {
            $rows = array_slice($rows, 0, $limit);
        }

        return array_values($rows);
    }

    /**
     * @param array<string, mixed> $values
     */
    public function queryInsert(string $table, array $values): string
    {
        $this->assertTable($table);
        $this->autoIncrement++;
        $id = (string) $this->autoIncrement;
        $row = $values;

        if (! array_key_exists('id', $row)) {
            $row['id'] = $this->autoIncrement;
        } else {
            $id = (string) $row['id'];
            $this->autoIncrement = max($this->autoIncrement, (int) $row['id']);
        }

        $this->tables[$table][] = $row;
        $this->lastInsertId = $id;

        return $id;
    }

    /**
     * @param array<string, mixed> $values
     * @param list<array{column: string, operator: string, value: mixed}> $wheres
     */
    public function queryUpdate(string $table, array $values, array $wheres): int
    {
        $this->assertTable($table);
        $affected = 0;

        foreach ($this->tables[$table] as $index => $row) {
            if (! $this->rowMatches($row, $wheres)) {
                continue;
            }
            $this->tables[$table][$index] = array_merge($row, $values);
            $affected++;
        }

        return $affected;
    }

    /**
     * @param list<array{column: string, operator: string, value: mixed}> $wheres
     */
    public function queryDelete(string $table, array $wheres): int
    {
        $this->assertTable($table);
        $before = count($this->tables[$table]);
        $this->tables[$table] = array_values(array_filter(
            $this->tables[$table],
            fn (array $row): bool => ! $this->rowMatches($row, $wheres)
        ));

        return $before - count($this->tables[$table]);
    }

    /**
     * @param list<array{column: string, operator: string, value: mixed}> $wheres
     */
    public function queryCount(string $table, array $wheres = []): int
    {
        return count($this->filter($table, $wheres));
    }

    /**
     * @param list<int|string> $ids
     * @return list<array<string, mixed>>
     */
    public function queryWhereIn(string $table, string $column, array $ids): array
    {
        $this->assertTable($table);
        $rows = [];

        foreach ($this->tables[$table] as $row) {
            if (in_array($row[$column] ?? null, $ids, false)) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    public function select(string $sql, array $bindings = []): array
    {
        throw new QueryException('MemoryConnection does not execute raw SQL. Use query helpers.');
    }

    public function selectOne(string $sql, array $bindings = []): ?array
    {
        throw new QueryException('MemoryConnection does not execute raw SQL. Use query helpers.');
    }

    public function statement(string $sql, array $bindings = []): bool
    {
        throw new QueryException('MemoryConnection does not execute raw SQL. Use schema/query helpers.');
    }

    public function affectingStatement(string $sql, array $bindings = []): int
    {
        throw new QueryException('MemoryConnection does not execute raw SQL. Use query helpers.');
    }

    public function lastInsertId(): string
    {
        return $this->lastInsertId;
    }

    public function beginTransaction(): void
    {
    }

    public function commit(): void
    {
    }

    public function rollBack(): void
    {
    }

    /**
     * @param list<array{column: string, operator: string, value: mixed}> $wheres
     * @return list<array<string, mixed>>
     */
    private function filter(string $table, array $wheres): array
    {
        $this->assertTable($table);

        return array_values(array_filter(
            $this->tables[$table],
            fn (array $row): bool => $this->rowMatches($row, $wheres)
        ));
    }

    /**
     * @param array<string, mixed> $row
     * @param list<array{
     *     boolean?: string,
     *     type?: string,
     *     column: string,
     *     operator?: string,
     *     value?: mixed,
     *     values?: list<mixed>,
     *     not?: bool
     * }> $wheres
     */
    private function rowMatches(array $row, array $wheres): bool
    {
        if ($wheres === []) {
            return true;
        }

        $matched = null;

        foreach ($wheres as $where) {
            $ok = $this->matchClause($row, $where);
            $boolean = strtoupper((string) ($where['boolean'] ?? 'AND'));

            if ($matched === null) {
                $matched = $ok;
                continue;
            }

            $matched = $boolean === 'OR' ? ($matched || $ok) : ($matched && $ok);
        }

        return (bool) $matched;
    }

    /**
     * @param array<string, mixed> $row
     * @param array{
     *     type?: string,
     *     column: string,
     *     operator?: string,
     *     value?: mixed,
     *     values?: list<mixed>,
     *     not?: bool
     * } $where
     */
    private function matchClause(array $row, array $where): bool
    {
        $type = $where['type'] ?? 'basic';
        $column = $where['column'];
        $left = $row[$column] ?? null;

        if ($type === 'in') {
            return in_array($left, $where['values'] ?? [], false);
        }

        if ($type === 'null') {
            $isNull = $left === null;
            return ($where['not'] ?? false) ? ! $isNull : $isNull;
        }

        $right = $where['value'] ?? null;
        $operator = $where['operator'] ?? '=';

        return match ($operator) {
            '=' => $left == $right,
            '!=', '<>' => $left != $right,
            '<' => $left < $right,
            '>' => $left > $right,
            '<=' => $left <= $right,
            '>=' => $left >= $right,
            'LIKE' => is_string($left) && is_string($right) && self::like($left, $right),
            default => false,
        };
    }

    private static function like(string $value, string $pattern): bool
    {
        $regex = '/^' . str_replace(['%', '_'], ['.*', '.'], preg_quote($pattern, '/')) . '$/i';

        return preg_match($regex, $value) === 1;
    }

    private function assertTable(string $table): void
    {
        if (! isset($this->tables[$table])) {
            throw new QueryException(sprintf('Table [%s] does not exist.', $table));
        }
    }
}
