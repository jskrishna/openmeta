<?php

declare(strict_types=1);

namespace OpenMeta\Database\Contracts;

/**
 * Database connection. All SQL uses placeholders — never concatenate untrusted input.
 */
interface ConnectionInterface
{
    public function driver(): string;

    public function prefix(): string;

    public function table(string $name): string;

    /**
     * @param array<int|string, mixed> $bindings
     * @return list<array<string, mixed>>
     */
    public function select(string $sql, array $bindings = []): array;

    /**
     * @param array<int|string, mixed> $bindings
     * @return array<string, mixed>|null
     */
    public function selectOne(string $sql, array $bindings = []): ?array;

    /**
     * @param array<int|string, mixed> $bindings
     */
    public function statement(string $sql, array $bindings = []): bool;

    /**
     * @param array<int|string, mixed> $bindings
     */
    public function affectingStatement(string $sql, array $bindings = []): int;

    public function lastInsertId(): string;

    public function beginTransaction(): void;

    public function commit(): void;

    public function rollBack(): void;
}
