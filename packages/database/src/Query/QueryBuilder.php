<?php

declare(strict_types=1);

namespace OpenMeta\Database\Query;

use OpenMeta\Database\Collections\ResultCollection;
use OpenMeta\Database\Connections\MemoryConnection;
use OpenMeta\Database\Contracts\ConnectionInterface;
use OpenMeta\Database\Exceptions\QueryException;
use OpenMeta\Database\Pagination\LengthAwarePaginator;
use OpenMeta\Database\Support\Identifier;

/**
 * Fluent, database-agnostic query builder. Compiles to prepared statements or memory helpers.
 * Not an Eloquent / Doctrine ORM.
 */
final class QueryBuilder
{
    private string $table = '';

    /**
     * @var list<array{
     *     boolean: string,
     *     type: string,
     *     column: string,
     *     operator?: string,
     *     value?: mixed,
     *     values?: list<mixed>,
     *     not?: bool
     * }>
     */
    private array $wheres = [];

    /** @var list<array{column: string, direction: string}> */
    private array $orders = [];

    /** @var list<string> */
    private array $groups = [];

    /** @var list<array{type: string, table: string, first: string, operator: string, second: string}> */
    private array $joins = [];

    private ?int $limit = null;

    private ?int $offset = null;

    public function __construct(private readonly ConnectionInterface $connection)
    {
    }

    public function from(string $table): self
    {
        $clone = clone $this;
        $clone->table = $this->connection->table($table);

        return $clone;
    }

    public function where(string $column, mixed $operator, mixed $value = null): self
    {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }

        return $this->addBasic('AND', (string) $operator, $column, $value);
    }

    public function orWhere(string $column, mixed $operator, mixed $value = null): self
    {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }

        return $this->addBasic('OR', (string) $operator, $column, $value);
    }

    /**
     * @param list<mixed> $values
     */
    public function whereIn(string $column, array $values): self
    {
        $clone = clone $this;
        $clone->wheres[] = [
            'boolean' => 'AND',
            'type' => 'in',
            'column' => $column,
            'values' => array_values($values),
        ];

        return $clone;
    }

    public function whereNull(string $column): self
    {
        $clone = clone $this;
        $clone->wheres[] = [
            'boolean' => 'AND',
            'type' => 'null',
            'column' => $column,
            'not' => false,
        ];

        return $clone;
    }

    public function whereNotNull(string $column): self
    {
        $clone = clone $this;
        $clone->wheres[] = [
            'boolean' => 'AND',
            'type' => 'null',
            'column' => $column,
            'not' => true,
        ];

        return $clone;
    }

    public function join(string $table, string $first, string $operator, string $second): self
    {
        return $this->addJoin('inner', $table, $first, $operator, $second);
    }

    public function leftJoin(string $table, string $first, string $operator, string $second): self
    {
        return $this->addJoin('left', $table, $first, $operator, $second);
    }

    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $direction = strtolower($direction) === 'desc' ? 'DESC' : 'ASC';
        $clone = clone $this;
        $clone->orders[] = ['column' => $column, 'direction' => $direction];

        return $clone;
    }

    public function groupBy(string ...$columns): self
    {
        $clone = clone $this;
        foreach ($columns as $column) {
            $clone->groups[] = $column;
        }

        return $clone;
    }

    public function limit(int $limit): self
    {
        $clone = clone $this;
        $clone->limit = max(0, $limit);

        return $clone;
    }

    public function offset(int $offset): self
    {
        $clone = clone $this;
        $clone->offset = max(0, $offset);

        return $clone;
    }

    /** @return list<array<string, mixed>> */
    public function get(): array
    {
        $this->assertTable();

        if ($this->connection instanceof MemoryConnection) {
            return $this->connection->querySelect(
                $this->table,
                $this->wheres,
                $this->orders,
                $this->limit,
                $this->offset
            );
        }

        [$sql, $bindings] = $this->compileSelect();

        return $this->connection->select($sql, $bindings);
    }

    public function getCollection(): ResultCollection
    {
        return new ResultCollection($this->get());
    }

    /** @return array<string, mixed>|null */
    public function first(): ?array
    {
        return $this->limit(1)->get()[0] ?? null;
    }

    public function count(): int
    {
        $this->assertTable();

        if ($this->connection instanceof MemoryConnection) {
            return $this->connection->queryCount($this->table, $this->wheres);
        }

        [$whereSql, $bindings] = $this->compileWheres();
        $sql = 'SELECT COUNT(*) AS aggregate FROM ' . $this->wrap($this->table)
            . $this->compileJoins() . $whereSql;
        $row = $this->connection->selectOne($sql, $bindings);

        return (int) ($row['aggregate'] ?? 0);
    }

    public function max(string $column): mixed
    {
        return $this->aggregate('MAX', $column);
    }

    public function min(string $column): mixed
    {
        return $this->aggregate('MIN', $column);
    }

    public function sum(string $column): mixed
    {
        return $this->aggregate('SUM', $column);
    }

    public function avg(string $column): mixed
    {
        return $this->aggregate('AVG', $column);
    }

    public function paginate(int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $total = $this->count();
        $items = $this->offset(($page - 1) * $perPage)->limit($perPage)->get();

        return new LengthAwarePaginator($items, $total, $perPage, $page);
    }

    /**
     * @param array<string, mixed> $values
     */
    public function insert(array $values): string
    {
        $this->assertTable();

        if ($this->connection instanceof MemoryConnection) {
            return $this->connection->queryInsert($this->table, $values);
        }

        $columns = array_keys($values);
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $columnSql = implode(', ', array_map(fn (string $c): string => $this->wrap($c), $columns));

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->wrap($this->table),
            $columnSql,
            $placeholders
        );

        $this->connection->statement($sql, array_values($values));

        return $this->connection->lastInsertId();
    }

    /**
     * @param array<string, mixed> $values
     */
    public function update(array $values): int
    {
        $this->assertTable();

        if ($this->connection instanceof MemoryConnection) {
            return $this->connection->queryUpdate($this->table, $values, $this->wheres);
        }

        $sets = [];
        $bindings = [];

        foreach ($values as $column => $value) {
            $sets[] = $this->wrap((string) $column) . ' = ?';
            $bindings[] = $value;
        }

        [$whereSql, $whereBindings] = $this->compileWheres();
        $sql = 'UPDATE ' . $this->wrap($this->table) . ' SET ' . implode(', ', $sets) . $whereSql;

        return $this->connection->affectingStatement($sql, [...$bindings, ...$whereBindings]);
    }

    public function delete(): int
    {
        $this->assertTable();

        if ($this->connection instanceof MemoryConnection) {
            return $this->connection->queryDelete($this->table, $this->wheres);
        }

        [$whereSql, $bindings] = $this->compileWheres();
        $sql = 'DELETE FROM ' . $this->wrap($this->table) . $whereSql;

        return $this->connection->affectingStatement($sql, $bindings);
    }

    private function addBasic(string $boolean, string $operator, string $column, mixed $value): self
    {
        $operator = strtoupper($operator);
        $allowed = ['=', '!=', '<>', '<', '>', '<=', '>=', 'LIKE'];

        if (! in_array($operator, $allowed, true)) {
            throw new QueryException(sprintf('Unsupported operator [%s].', $operator));
        }

        $clone = clone $this;
        $clone->wheres[] = [
            'boolean' => $boolean,
            'type' => 'basic',
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
        ];

        return $clone;
    }

    private function addJoin(
        string $type,
        string $table,
        string $first,
        string $operator,
        string $second
    ): self {
        $clone = clone $this;
        $clone->joins[] = [
            'type' => $type,
            'table' => $this->connection->table($table),
            'first' => $first,
            'operator' => $operator,
            'second' => $second,
        ];

        return $clone;
    }

    private function aggregate(string $fn, string $column): mixed
    {
        $this->assertTable();

        if ($this->connection instanceof MemoryConnection) {
            $rows = $this->connection->querySelect($this->table, $this->wheres, [], null, null);
            if ($rows === []) {
                return null;
            }
            $values = array_map(static fn (array $row): mixed => $row[$column] ?? null, $rows);
            $values = array_values(array_filter($values, static fn (mixed $v): bool => is_numeric($v)));

            return match ($fn) {
                'MAX' => $values === [] ? null : max($values),
                'MIN' => $values === [] ? null : min($values),
                'SUM' => array_sum($values),
                'AVG' => $values === [] ? null : array_sum($values) / count($values),
                default => null,
            };
        }

        [$whereSql, $bindings] = $this->compileWheres();
        $sql = sprintf(
            'SELECT %s(%s) AS aggregate FROM %s%s%s',
            $fn,
            $this->wrap($column),
            $this->wrap($this->table),
            $this->compileJoins(),
            $whereSql
        );
        $row = $this->connection->selectOne($sql, $bindings);

        return $row['aggregate'] ?? null;
    }

    /**
     * @return array{0: string, 1: list<mixed>}
     */
    private function compileSelect(): array
    {
        [$whereSql, $bindings] = $this->compileWheres();
        $sql = 'SELECT * FROM ' . $this->wrap($this->table) . $this->compileJoins() . $whereSql;

        if ($this->groups !== []) {
            $sql .= ' GROUP BY ' . implode(', ', array_map(fn (string $c): string => $this->wrap($c), $this->groups));
        }

        if ($this->orders !== []) {
            $parts = [];
            foreach ($this->orders as $order) {
                $parts[] = $this->wrap($order['column']) . ' ' . $order['direction'];
            }
            $sql .= ' ORDER BY ' . implode(', ', $parts);
        }

        if ($this->limit !== null) {
            $sql .= ' LIMIT ' . $this->limit;
        }

        if ($this->offset !== null) {
            $sql .= ' OFFSET ' . $this->offset;
        }

        return [$sql, $bindings];
    }

    private function compileJoins(): string
    {
        if ($this->joins === []) {
            return '';
        }

        $sql = '';
        foreach ($this->joins as $join) {
            $type = strtoupper($join['type']) === 'LEFT' ? 'LEFT JOIN' : 'INNER JOIN';
            $sql .= sprintf(
                ' %s %s ON %s %s %s',
                $type,
                $this->wrap($join['table']),
                $this->wrap($join['first']),
                $join['operator'],
                $this->wrap($join['second'])
            );
        }

        return $sql;
    }

    /**
     * @return array{0: string, 1: list<mixed>}
     */
    private function compileWheres(): array
    {
        if ($this->wheres === []) {
            return ['', []];
        }

        $parts = [];
        $bindings = [];

        foreach ($this->wheres as $index => $where) {
            $boolean = $index === 0 ? '' : ' ' . $where['boolean'] . ' ';
            $fragment = match ($where['type']) {
                'in' => $this->compileIn($where, $bindings),
                'null' => $this->wrap($where['column']) . (($where['not'] ?? false) ? ' IS NOT NULL' : ' IS NULL'),
                default => $this->wrap($where['column']) . ' ' . ($where['operator'] ?? '=') . ' ?',
            };

            if ($where['type'] === 'basic') {
                $bindings[] = $where['value'] ?? null;
            }

            $parts[] = $boolean . $fragment;
        }

        return [' WHERE ' . implode('', $parts), $bindings];
    }

    /**
     * @param array{column: string, values?: list<mixed>} $where
     * @param list<mixed> $bindings
     */
    private function compileIn(array $where, array &$bindings): string
    {
        $values = $where['values'] ?? [];
        if ($values === []) {
            return '0 = 1';
        }

        foreach ($values as $value) {
            $bindings[] = $value;
        }

        return $this->wrap($where['column']) . ' IN (' . implode(', ', array_fill(0, count($values), '?')) . ')';
    }

    private function wrap(string $value): string
    {
        return Identifier::quote($value, $this->connection->driver());
    }

    private function assertTable(): void
    {
        if ($this->table === '') {
            throw new QueryException('Query table is not set. Call from($table) first.');
        }
    }
}
