<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Tests;

use OpenMeta\Core\Events\EventDispatcher;
use OpenMeta\GraphQL\Authorization\FieldAuthorizer;
use OpenMeta\GraphQL\Directives\DirectiveRegistry;
use OpenMeta\GraphQL\Errors\ErrorHandler;
use OpenMeta\GraphQL\Inputs\InputRegistry;
use OpenMeta\GraphQL\Interfaces\InterfaceRegistry;
use OpenMeta\GraphQL\Manager\GraphQLManager;
use OpenMeta\GraphQL\Mutations\MutationRegistry;
use OpenMeta\GraphQL\Queries\QueryRegistry;
use OpenMeta\GraphQL\Resolvers\CallableResolver;
use OpenMeta\GraphQL\Resolvers\ResolverRegistry;
use OpenMeta\GraphQL\Scalars\ScalarRegistry;
use OpenMeta\GraphQL\Schema\SchemaBuilder;
use OpenMeta\GraphQL\Schema\SchemaManager;
use OpenMeta\GraphQL\Schema\SchemaRegistries;
use OpenMeta\GraphQL\Schema\SchemaRegistry;
use OpenMeta\GraphQL\Schema\SchemaValidator;
use OpenMeta\GraphQL\Support\IntrospectionGenerator;
use OpenMeta\GraphQL\Support\OperationExecutor;
use OpenMeta\GraphQL\Support\SchemaPrinter;
use OpenMeta\GraphQL\Tests\Fixtures\StubGate;
use OpenMeta\GraphQL\Types\FieldDefinition;
use OpenMeta\GraphQL\Types\TypeRegistry;
use OpenMeta\GraphQL\Unions\UnionRegistry;
use OpenMeta\GraphQL\Validation\RuleInputValidator;
use PHPUnit\Framework\TestCase;

/**
 * Base test case wiring a full GraphQL stack in-memory (no container).
 */
abstract class GraphQLTestCase extends TestCase
{
    protected EventDispatcher $events;

    protected SchemaRegistries $registries;

    protected SchemaManager $schemaManager;

    protected OperationExecutor $executor;

    protected GraphQLManager $graphql;

    protected StubGate $gate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->events = new EventDispatcher();

        $scalars = new ScalarRegistry();
        $scalars->registerDefaults();
        $directives = new DirectiveRegistry();
        $directives->registerDefaults();

        $this->registries = new SchemaRegistries(
            new TypeRegistry(),
            $scalars,
            new InputRegistry(),
            new InterfaceRegistry(),
            new UnionRegistry(),
            $directives,
            new QueryRegistry(),
            new MutationRegistry(),
            new ResolverRegistry(),
        );

        $this->schemaManager = new SchemaManager(
            $this->registries,
            new SchemaBuilder(),
            new SchemaValidator(),
            new SchemaRegistry(),
            $this->events,
        );

        $this->gate = new StubGate();

        $this->executor = new OperationExecutor(
            $this->registries,
            new FieldAuthorizer($this->gate),
            new RuleInputValidator(),
            new ErrorHandler(),
            $this->events,
        );

        $this->graphql = new GraphQLManager(
            $this->schemaManager,
            $this->registries,
            $this->executor,
            new SchemaPrinter(),
            new IntrospectionGenerator(),
        );
    }

    /**
     * Register a resolver and return its name.
     *
     * @param callable(mixed, array<string, mixed>, \OpenMeta\GraphQL\Resolvers\ResolutionContext): mixed $callback
     */
    protected function resolver(string $name, callable $callback): string
    {
        $this->registries->resolvers->register($name, new CallableResolver($callback));

        return $name;
    }

    /**
     * Register a captured-events listener; returns a list reference container.
     *
     * @param class-string $event
     * @param list<object> $sink
     */
    protected function capture(string $event, array &$sink): void
    {
        $this->events->listen($event, function (object $e) use (&$sink): void {
            $sink[] = $e;
        });
    }

    protected function query(FieldDefinition $field): void
    {
        $this->registries->queries->register($field);
    }

    protected function mutation(FieldDefinition $field): void
    {
        $this->registries->mutations->register($field);
    }
}
