<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Manager;

use OpenMeta\GraphQL\Contracts\GraphQLManagerInterface;
use OpenMeta\GraphQL\Contracts\MutationRegistryInterface;
use OpenMeta\GraphQL\Contracts\QueryRegistryInterface;
use OpenMeta\GraphQL\Contracts\SchemaManagerInterface;
use OpenMeta\GraphQL\Contracts\TypeRegistryInterface;
use OpenMeta\GraphQL\Resolvers\ResolutionContext;
use OpenMeta\GraphQL\Schema\Schema;
use OpenMeta\GraphQL\Schema\SchemaRegistries;
use OpenMeta\GraphQL\Support\IntrospectionGenerator;
use OpenMeta\GraphQL\Support\OperationExecutor;
use OpenMeta\GraphQL\Support\SchemaPrinter;

/**
 * Public façade for the GraphQL abstraction layer.
 *
 * A thin composition over the schema manager, registries, and executor — it
 * holds no schema or execution logic of its own.
 */
final class GraphQLManager implements GraphQLManagerInterface
{
    public function __construct(
        private readonly SchemaManagerInterface $schemaManager,
        private readonly SchemaRegistries $registries,
        private readonly OperationExecutor $executor,
        private readonly SchemaPrinter $printer,
        private readonly IntrospectionGenerator $introspection,
    ) {
    }

    public function schema(): Schema
    {
        return $this->schemaManager->schema();
    }

    public function types(): TypeRegistryInterface
    {
        return $this->registries->types;
    }

    public function queries(): QueryRegistryInterface
    {
        return $this->registries->queries;
    }

    public function mutations(): MutationRegistryInterface
    {
        return $this->registries->mutations;
    }

    public function executeQuery(string $name, array $args = [], ?ResolutionContext $context = null): ExecutionResult
    {
        return $this->executor->executeQuery($name, $args, $context);
    }

    public function executeMutation(string $name, array $args = [], ?ResolutionContext $context = null): ExecutionResult
    {
        return $this->executor->executeMutation($name, $args, $context);
    }

    public function sdl(): string
    {
        return $this->printer->print($this->schema());
    }

    public function introspect(): array
    {
        return $this->introspection->generate($this->schema());
    }
}
