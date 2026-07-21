<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Mutations;

use OpenMeta\GraphQL\Contracts\MutationRegistryInterface;
use OpenMeta\GraphQL\Errors\DuplicateTypeException;
use OpenMeta\GraphQL\Errors\TypeNotFoundException;
use OpenMeta\GraphQL\Types\FieldDefinition;

/**
 * Registry of root mutation fields, with optional discovery.
 */
final class MutationRegistry implements MutationRegistryInterface
{
    /** @var array<string, FieldDefinition> */
    private array $mutations = [];

    public function register(FieldDefinition $mutation): void
    {
        if (isset($this->mutations[$mutation->name])) {
            throw DuplicateTypeException::named('Mutation', $mutation->name);
        }

        $this->mutations[$mutation->name] = $mutation;
    }

    /**
     * @param iterable<FieldDefinition> $mutations
     */
    public function discover(iterable $mutations): void
    {
        foreach ($mutations as $mutation) {
            $this->register($mutation);
        }
    }

    public function has(string $name): bool
    {
        return isset($this->mutations[$name]);
    }

    public function get(string $name): FieldDefinition
    {
        return $this->mutations[$name] ?? throw TypeNotFoundException::mutation($name);
    }

    public function all(): array
    {
        return array_values($this->mutations);
    }
}
