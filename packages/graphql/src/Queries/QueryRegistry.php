<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Queries;

use OpenMeta\GraphQL\Contracts\QueryRegistryInterface;
use OpenMeta\GraphQL\Errors\DuplicateTypeException;
use OpenMeta\GraphQL\Errors\TypeNotFoundException;
use OpenMeta\GraphQL\Types\FieldDefinition;

/**
 * Registry of root query fields, with optional grouping and discovery.
 */
final class QueryRegistry implements QueryRegistryInterface
{
    /** @var array<string, FieldDefinition> */
    private array $queries = [];

    public function register(FieldDefinition $query): void
    {
        if (isset($this->queries[$query->name])) {
            throw DuplicateTypeException::named('Query', $query->name);
        }

        $this->queries[$query->name] = $query;
    }

    /**
     * Discover queries from any iterable source (e.g. extension providers).
     *
     * @param iterable<FieldDefinition> $queries
     */
    public function discover(iterable $queries): void
    {
        foreach ($queries as $query) {
            $this->register($query);
        }
    }

    public function has(string $name): bool
    {
        return isset($this->queries[$name]);
    }

    public function get(string $name): FieldDefinition
    {
        return $this->queries[$name] ?? throw TypeNotFoundException::query($name);
    }

    public function all(): array
    {
        return array_values($this->queries);
    }

    public function group(string $group): array
    {
        return array_values(array_filter(
            $this->queries,
            static fn (FieldDefinition $query): bool => $query->group === $group,
        ));
    }
}
