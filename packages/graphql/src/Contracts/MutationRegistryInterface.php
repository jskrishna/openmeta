<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Contracts;

use OpenMeta\GraphQL\Errors\TypeNotFoundException;
use OpenMeta\GraphQL\Types\FieldDefinition;

/**
 * Registry of root mutation fields.
 */
interface MutationRegistryInterface
{
    public function register(FieldDefinition $mutation): void;

    public function has(string $name): bool;

    /**
     * @throws TypeNotFoundException
     */
    public function get(string $name): FieldDefinition;

    /**
     * @return list<FieldDefinition>
     */
    public function all(): array;
}
