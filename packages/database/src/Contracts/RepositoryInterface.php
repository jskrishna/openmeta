<?php

declare(strict_types=1);

namespace OpenMeta\Database\Contracts;

use OpenMeta\Database\Query\QueryBuilder;

/**
 * Persistence façade for consumers. No HTTP / field rendering.
 *
 * @phpstan-type Row array<string, mixed>
 */
interface RepositoryInterface
{
    /** @return Row|null */
    public function find(int|string $id): ?array;

    /** @return list<Row> */
    public function all(): array;

    /**
     * @param Row $attributes
     * @return Row
     */
    public function create(array $attributes): array;

    /**
     * @param Row $attributes
     * @return Row|null
     */
    public function update(int|string $id, array $attributes): ?array;

    public function delete(int|string $id): bool;

    public function query(): QueryBuilder;
}
