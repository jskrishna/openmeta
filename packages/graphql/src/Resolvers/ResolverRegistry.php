<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Resolvers;

use OpenMeta\GraphQL\Contracts\ResolverInterface;
use OpenMeta\GraphQL\Contracts\ResolverRegistryInterface;
use OpenMeta\GraphQL\Errors\TypeNotFoundException;

/**
 * Registry of named resolvers.
 */
final class ResolverRegistry implements ResolverRegistryInterface
{
    /** @var array<string, ResolverInterface> */
    private array $resolvers = [];

    public function register(string $name, ResolverInterface $resolver): void
    {
        $this->resolvers[$name] = $resolver;
    }

    public function has(string $name): bool
    {
        return isset($this->resolvers[$name]);
    }

    public function get(string $name): ResolverInterface
    {
        return $this->resolvers[$name] ?? throw TypeNotFoundException::resolver($name);
    }
}
