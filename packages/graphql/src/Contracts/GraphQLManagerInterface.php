<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Contracts;

use OpenMeta\GraphQL\Manager\ExecutionResult;
use OpenMeta\GraphQL\Resolvers\ResolutionContext;
use OpenMeta\GraphQL\Schema\Schema;

/**
 * Public façade for the GraphQL abstraction layer.
 */
interface GraphQLManagerInterface
{
    public function schema(): Schema;

    public function types(): TypeRegistryInterface;

    public function queries(): QueryRegistryInterface;

    public function mutations(): MutationRegistryInterface;

    /**
     * @param array<string, mixed> $args
     */
    public function executeQuery(string $name, array $args = [], ?ResolutionContext $context = null): ExecutionResult;

    /**
     * @param array<string, mixed> $args
     */
    public function executeMutation(
        string $name,
        array $args = [],
        ?ResolutionContext $context = null,
    ): ExecutionResult;

    public function sdl(): string;

    /**
     * @return array<string, mixed>
     */
    public function introspect(): array;
}
