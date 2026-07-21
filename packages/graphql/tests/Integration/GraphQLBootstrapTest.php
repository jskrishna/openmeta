<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Tests\Integration;

use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\GraphQL\Contracts\GraphQLManagerInterface;
use OpenMeta\GraphQL\GraphQLServiceProvider;
use OpenMeta\GraphQL\Resolvers\CallableResolver;
use OpenMeta\GraphQL\Resolvers\ResolverRegistry;
use OpenMeta\GraphQL\Types\FieldDefinition;
use OpenMeta\GraphQL\Types\TypeReference;
use OpenMeta\Security\SecurityServiceProvider;
use OpenMeta\Validation\ValidationServiceProvider;
use PHPUnit\Framework\TestCase;

/**
 * Boots the framework with the GraphQL provider and exercises the wired stack.
 */
final class GraphQLBootstrapTest extends TestCase
{
    private function boot(): \OpenMeta\Core\Application\Application
    {
        return Bootstrap::run(
            ['app' => ['key' => 'graphql-test-secret']],
            [
                ValidationServiceProvider::class,
                SecurityServiceProvider::class,
                GraphQLServiceProvider::class,
            ],
        );
    }

    public function test_manager_resolves_and_executes(): void
    {
        $app = $this->boot();

        /** @var GraphQLManagerInterface $graphql */
        $graphql = $app->get(GraphQLManagerInterface::class);
        /** @var ResolverRegistry $resolvers */
        $resolvers = $app->get(ResolverRegistry::class);

        $resolvers->register('year', new CallableResolver(static fn (): string => '2026'));
        $graphql->queries()->register(new FieldDefinition('year', TypeReference::named('String'), resolver: 'year'));

        $result = $graphql->executeQuery('year');

        self::assertFalse($result->hasErrors());
        self::assertSame('2026', $result->data);
        self::assertSame($graphql, $app->get('graphql'));
    }

    public function test_sdl_and_introspection_available(): void
    {
        $app = $this->boot();

        /** @var GraphQLManagerInterface $graphql */
        $graphql = $app->get(GraphQLManagerInterface::class);
        /** @var ResolverRegistry $resolvers */
        $resolvers = $app->get(ResolverRegistry::class);

        $resolvers->register('ping', new CallableResolver(static fn (): string => 'pong'));
        $graphql->queries()->register(new FieldDefinition('ping', TypeReference::named('String'), resolver: 'ping'));

        self::assertStringContainsString('type Query {', $graphql->sdl());
        self::assertArrayHasKey('__schema', $graphql->introspect());
    }
}
