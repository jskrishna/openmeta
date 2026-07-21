<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Resolvers;

use OpenMeta\GraphQL\Contracts\ResolverInterface;

/**
 * Adapts a callable into a {@see ResolverInterface}.
 */
final class CallableResolver implements ResolverInterface
{
    /** @var callable(mixed, array<string, mixed>, ResolutionContext): mixed */
    private $callback;

    /**
     * @param callable(mixed, array<string, mixed>, ResolutionContext): mixed $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function resolve(mixed $root, array $args, ResolutionContext $context): mixed
    {
        return ($this->callback)($root, $args, $context);
    }
}
