<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL;

use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\GraphQL\Authorization\FieldAuthorizer;
use OpenMeta\GraphQL\Contracts\ErrorHandlerInterface;
use OpenMeta\GraphQL\Contracts\FieldAuthorizerInterface;
use OpenMeta\GraphQL\Contracts\GraphQLManagerInterface;
use OpenMeta\GraphQL\Contracts\InputValidatorInterface;
use OpenMeta\GraphQL\Contracts\MutationRegistryInterface;
use OpenMeta\GraphQL\Contracts\QueryRegistryInterface;
use OpenMeta\GraphQL\Contracts\ResolverRegistryInterface;
use OpenMeta\GraphQL\Contracts\SchemaManagerInterface;
use OpenMeta\GraphQL\Contracts\TypeRegistryInterface;
use OpenMeta\GraphQL\Directives\DirectiveRegistry;
use OpenMeta\GraphQL\Errors\ErrorHandler;
use OpenMeta\GraphQL\Inputs\InputRegistry;
use OpenMeta\GraphQL\Interfaces\InterfaceRegistry;
use OpenMeta\GraphQL\Manager\GraphQLManager;
use OpenMeta\GraphQL\Mutations\MutationRegistry;
use OpenMeta\GraphQL\Queries\QueryRegistry;
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
use OpenMeta\GraphQL\Support\SubscriptionRegistry;
use OpenMeta\GraphQL\Types\TypeRegistry;
use OpenMeta\GraphQL\Unions\UnionRegistry;
use OpenMeta\GraphQL\Validation\RuleInputValidator;
use OpenMeta\Security\Contracts\GateInterface;

/**
 * Registers the GraphQL abstraction layer into the container.
 *
 * Reuses Security (permission gate) and Validation (rule engine) for the
 * authorization and validation integrations — never re-implementing them.
 */
final class GraphQLServiceProvider extends ServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        $this->registerRegistries($container);
        $this->registerSchema($container);
        $this->registerExecution($container);
        $this->registerManager($container);
    }

    private function registerRegistries(ContainerInterface $container): void
    {
        $container->singleton(ScalarRegistry::class, static function (): ScalarRegistry {
            $registry = new ScalarRegistry();
            $registry->registerDefaults();

            return $registry;
        });

        $container->singleton(DirectiveRegistry::class, static function (): DirectiveRegistry {
            $registry = new DirectiveRegistry();
            $registry->registerDefaults();

            return $registry;
        });

        $container->singleton(TypeRegistry::class, static fn (): TypeRegistry => new TypeRegistry());
        $container->alias(TypeRegistry::class, TypeRegistryInterface::class);
        $container->singleton(InputRegistry::class, static fn (): InputRegistry => new InputRegistry());
        $container->singleton(InterfaceRegistry::class, static fn (): InterfaceRegistry => new InterfaceRegistry());
        $container->singleton(UnionRegistry::class, static fn (): UnionRegistry => new UnionRegistry());
        $container->singleton(ResolverRegistry::class, static fn (): ResolverRegistry => new ResolverRegistry());
        $container->alias(ResolverRegistry::class, ResolverRegistryInterface::class);

        $container->singleton(QueryRegistry::class, static fn (): QueryRegistry => new QueryRegistry());
        $container->alias(QueryRegistry::class, QueryRegistryInterface::class);
        $container->singleton(MutationRegistry::class, static fn (): MutationRegistry => new MutationRegistry());
        $container->alias(MutationRegistry::class, MutationRegistryInterface::class);

        $container->singleton(
            SubscriptionRegistry::class,
            static fn (): SubscriptionRegistry => new SubscriptionRegistry(),
        );

        $container->singleton(SchemaRegistries::class, static function (ContainerInterface $c): SchemaRegistries {
            return new SchemaRegistries(
                $c->get(TypeRegistry::class),
                $c->get(ScalarRegistry::class),
                $c->get(InputRegistry::class),
                $c->get(InterfaceRegistry::class),
                $c->get(UnionRegistry::class),
                $c->get(DirectiveRegistry::class),
                $c->get(QueryRegistry::class),
                $c->get(MutationRegistry::class),
                $c->get(ResolverRegistry::class),
            );
        });
    }

    private function registerSchema(ContainerInterface $container): void
    {
        $container->singleton(SchemaBuilder::class, static fn (): SchemaBuilder => new SchemaBuilder());
        $container->singleton(SchemaValidator::class, static fn (): SchemaValidator => new SchemaValidator());
        $container->singleton(SchemaRegistry::class, static fn (): SchemaRegistry => new SchemaRegistry());

        $container->singleton(SchemaManager::class, static function (ContainerInterface $c): SchemaManager {
            return new SchemaManager(
                $c->get(SchemaRegistries::class),
                $c->get(SchemaBuilder::class),
                $c->get(SchemaValidator::class),
                $c->get(SchemaRegistry::class),
                $c->get(EventDispatcherInterface::class),
            );
        });
        $container->alias(SchemaManager::class, SchemaManagerInterface::class);
    }

    private function registerExecution(ContainerInterface $container): void
    {
        $container->singleton(FieldAuthorizer::class, static function (ContainerInterface $c): FieldAuthorizer {
            return new FieldAuthorizer($c->get(GateInterface::class));
        });
        $container->alias(FieldAuthorizer::class, FieldAuthorizerInterface::class);

        $container->singleton(RuleInputValidator::class, static fn (): RuleInputValidator => new RuleInputValidator());
        $container->alias(RuleInputValidator::class, InputValidatorInterface::class);

        $container->singleton(ErrorHandler::class, static fn (): ErrorHandler => new ErrorHandler());
        $container->alias(ErrorHandler::class, ErrorHandlerInterface::class);

        $container->singleton(OperationExecutor::class, static function (ContainerInterface $c): OperationExecutor {
            return new OperationExecutor(
                $c->get(SchemaRegistries::class),
                $c->get(FieldAuthorizerInterface::class),
                $c->get(InputValidatorInterface::class),
                $c->get(ErrorHandlerInterface::class),
                $c->get(EventDispatcherInterface::class),
            );
        });
    }

    private function registerManager(ContainerInterface $container): void
    {
        $container->singleton(SchemaPrinter::class, static fn (): SchemaPrinter => new SchemaPrinter());
        $container->singleton(
            IntrospectionGenerator::class,
            static fn (): IntrospectionGenerator => new IntrospectionGenerator(),
        );

        $container->singleton(GraphQLManager::class, static function (ContainerInterface $c): GraphQLManager {
            return new GraphQLManager(
                $c->get(SchemaManagerInterface::class),
                $c->get(SchemaRegistries::class),
                $c->get(OperationExecutor::class),
                $c->get(SchemaPrinter::class),
                $c->get(IntrospectionGenerator::class),
            );
        });
        $container->alias(GraphQLManager::class, GraphQLManagerInterface::class);
        $container->alias(GraphQLManager::class, 'graphql');
    }
}
