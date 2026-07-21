<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Contracts;

use OpenMeta\GraphQL\Errors\TypeNotFoundException;
use OpenMeta\GraphQL\Types\FieldDefinition;

/**
 * Registry of root query fields.
 */
interface QueryRegistryInterface
{
    public function register(FieldDefinition $query): void;

    public function has(string $name): bool;

    /**
     * @throws TypeNotFoundException
     */
    public function get(string $name): FieldDefinition;

    /**
     * @return list<FieldDefinition>
     */
    public function all(): array;

    /**
     * @return list<FieldDefinition>
     */
    public function group(string $group): array;
}
