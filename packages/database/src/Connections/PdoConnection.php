<?php

declare(strict_types=1);

namespace OpenMeta\Database\Connections;

use OpenMeta\Database\Contracts\ConnectionInterface;
use OpenMeta\Database\Exceptions\QueryException;
use PDO;
use PDOException;
use PDOStatement;

/**
 * PDO-backed connection (SQLite for CI/tests; MySQL/MariaDB in production configs).
 */
final class PdoConnection implements ConnectionInterface
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly string $driver,
        private readonly string $prefix = '',
    ) {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    /**
     * @param array<string, mixed> $config
     */
    public static function fromConfig(array $config): self
    {
        $driver = (string) ($config['driver'] ?? 'sqlite');
        $prefix = (string) ($config['prefix'] ?? '');

        if ($driver === 'sqlite') {
            $database = (string) ($config['database'] ?? ':memory:');
            $pdo = new PDO('sqlite:' . $database);

            return new self($pdo, 'sqlite', $prefix);
        }

        if ($driver === 'mysql' || $driver === 'mariadb') {
            $host = (string) ($config['host'] ?? '127.0.0.1');
            $port = (string) ($config['port'] ?? '3306');
            $database = (string) ($config['database'] ?? '');
            $charset = (string) ($config['charset'] ?? 'utf8mb4');
            $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $host, $port, $database, $charset);
            $pdo = new PDO(
                $dsn,
                (string) ($config['username'] ?? ''),
                (string) ($config['password'] ?? ''),
            );

            return new self($pdo, $driver === 'mariadb' ? 'mysql' : $driver, $prefix);
        }

        throw new QueryException(sprintf('Unsupported database driver [%s].', $driver));
    }

    public static function sqliteMemory(string $prefix = 'om_'): self
    {
        return self::fromConfig([
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => $prefix,
        ]);
    }

    public function driver(): string
    {
        return $this->driver;
    }

    public function prefix(): string
    {
        return $this->prefix;
    }

    public function table(string $name): string
    {
        return $this->prefix . $name;
    }

    public function select(string $sql, array $bindings = []): array
    {
        $statement = $this->run($sql, $bindings);
        /** @var list<array<string, mixed>> */
        $rows = $statement->fetchAll();

        return $rows;
    }

    public function selectOne(string $sql, array $bindings = []): ?array
    {
        $statement = $this->run($sql, $bindings);
        $row = $statement->fetch();

        return $row === false ? null : $row;
    }

    public function statement(string $sql, array $bindings = []): bool
    {
        $this->run($sql, $bindings);

        return true;
    }

    public function affectingStatement(string $sql, array $bindings = []): int
    {
        return $this->run($sql, $bindings)->rowCount();
    }

    public function lastInsertId(): string
    {
        return (string) $this->pdo->lastInsertId();
    }

    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    public function commit(): void
    {
        $this->pdo->commit();
    }

    public function rollBack(): void
    {
        $this->pdo->rollBack();
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * @param array<int|string, mixed> $bindings
     */
    private function run(string $sql, array $bindings): PDOStatement
    {
        try {
            $statement = $this->pdo->prepare($sql);
            $statement->execute(array_values($bindings));

            return $statement;
        } catch (PDOException $e) {
            throw new QueryException($e->getMessage(), 0, $e);
        }
    }
}
