<?php

declare(strict_types=1);

namespace OpenMeta\Database\Schema;

use OpenMeta\Database\Connections\MemoryConnection;
use OpenMeta\Database\Contracts\ConnectionInterface;
use OpenMeta\Database\Exceptions\DatabaseException;

/**
 * Compiles Blueprint definitions and applies DDL via Connection.
 */
final class Schema
{
    public function __construct(private readonly ConnectionInterface $connection)
    {
    }

    /**
     * @param callable(Blueprint): void $callback
     */
    public function create(string $table, callable $callback): void
    {
        $blueprint = new Blueprint($this->connection->table($table));
        $callback($blueprint);

        if ($this->connection instanceof MemoryConnection) {
            $columns = array_map(
                static fn (array $column): string => $column['name'],
                $blueprint->columns()
            );
            $this->connection->createTable($blueprint->table(), $columns);

            return;
        }

        $this->connection->statement($this->compileCreate($blueprint));

        foreach ($blueprint->indexes() as $column) {
            $indexName = $blueprint->table() . '_' . $column . '_index';
            $sql = sprintf(
                'CREATE INDEX %s ON %s (%s)',
                $this->quoteIdentifier($indexName),
                $this->quoteIdentifier($blueprint->table()),
                $this->quoteIdentifier($column)
            );
            $this->connection->statement($sql);
        }
    }

    public function drop(string $table): void
    {
        $name = $this->connection->table($table);

        if ($this->connection instanceof MemoryConnection) {
            $this->connection->dropTable($name);

            return;
        }

        $this->connection->statement('DROP TABLE IF EXISTS ' . $this->quoteIdentifier($name));
    }

    public function hasTable(string $table): bool
    {
        $name = $this->connection->table($table);

        if ($this->connection instanceof MemoryConnection) {
            return $this->connection->hasTable($name);
        }

        if ($this->connection->driver() === 'sqlite') {
            $row = $this->connection->selectOne(
                "SELECT name FROM sqlite_master WHERE type = 'table' AND name = ?",
                [$name]
            );

            return $row !== null;
        }

        $row = $this->connection->selectOne('SHOW TABLES LIKE ?', [$name]);

        return $row !== null;
    }

    private function compileCreate(Blueprint $blueprint): string
    {
        $columns = [];

        foreach ($blueprint->columns() as $column) {
            $columns[] = $this->compileColumn($column);
        }

        if ($columns === []) {
            throw new DatabaseException('Cannot create a table without columns.');
        }

        return sprintf(
            'CREATE TABLE %s (%s)',
            $this->quoteIdentifier($blueprint->table()),
            implode(', ', $columns)
        );
    }

    /**
     * @param array{
     *     name: string,
     *     type: string,
     *     length: int|null,
     *     nullable: bool,
     *     primary: bool,
     *     autoIncrement: bool,
     *     default: mixed
     * } $column
     */
    private function compileColumn(array $column): string
    {
        $driver = $this->connection->driver();
        $sql = $this->quoteIdentifier($column['name']) . ' ' . $this->typeSql($column, $driver);

        if ($column['primary'] && $column['type'] === 'id') {
            return $sql;
        }

        if (! $column['nullable']) {
            $sql .= ' NOT NULL';
        }

        if ($column['primary']) {
            $sql .= ' PRIMARY KEY';
        }

        return $sql;
    }

    /**
     * @param array{
     *     name: string,
     *     type: string,
     *     length: int|null,
     *     nullable: bool,
     *     primary: bool,
     *     autoIncrement: bool,
     *     default: mixed
     * } $column
     */
    private function typeSql(array $column, string $driver): string
    {
        return match ($column['type']) {
            'id' => $driver === 'sqlite'
                ? 'INTEGER PRIMARY KEY AUTOINCREMENT'
                : 'BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'string' => $driver === 'sqlite'
                ? 'TEXT'
                : 'VARCHAR(' . ($column['length'] ?? 255) . ')',
            'text' => 'TEXT',
            'integer' => $driver === 'sqlite' ? 'INTEGER' : 'INT',
            'boolean' => $driver === 'sqlite' ? 'INTEGER' : 'TINYINT(1)',
            default => throw new DatabaseException('Unknown column type [' . $column['type'] . '].'),
        };
    }

    private function quoteIdentifier(string $name): string
    {
        if ($this->connection->driver() === 'sqlite') {
            return '"' . str_replace('"', '""', $name) . '"';
        }

        return '`' . str_replace('`', '``', $name) . '`';
    }
}
