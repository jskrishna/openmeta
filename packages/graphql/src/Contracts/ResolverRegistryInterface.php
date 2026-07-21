<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Contracts;

use OpenMeta\GraphQL\Errors\TypeNotFoundException;

/**
 * Stores resolvers by name.
 */
interface ResolverRegistryInterface
{
    public function register(string $name, ResolverInterface $resolver): void;

    public function has(string $name): bool;

    /**
     * @throws TypeNotFoundException
     */
    public function get(string $name): ResolverInterface;
}
