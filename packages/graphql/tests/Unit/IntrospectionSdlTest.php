<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Tests\Unit;

use OpenMeta\GraphQL\Tests\GraphQLTestCase;
use OpenMeta\GraphQL\Types\FieldDefinition;
use OpenMeta\GraphQL\Types\ObjectType;
use OpenMeta\GraphQL\Types\TypeReference;

final class IntrospectionSdlTest extends GraphQLTestCase
{
    private function seedSchema(): void
    {
        $this->registries->types->register(new ObjectType('Post', [
            new FieldDefinition('id', TypeReference::named('ID')->nonNull()),
            new FieldDefinition('title', TypeReference::named('String')),
        ]));
        $this->resolver('posts', static fn (): array => []);
        $this->query(new FieldDefinition('posts', TypeReference::named('Post')->listOf(true), resolver: 'posts'));
    }

    public function test_sdl_contains_types(): void
    {
        $this->seedSchema();
        $sdl = $this->graphql->sdl();

        self::assertStringContainsString('type Query {', $sdl);
        self::assertStringContainsString('posts: [Post!]', $sdl);
        self::assertStringContainsString('type Post {', $sdl);
        self::assertStringContainsString('id: ID!', $sdl);
    }

    public function test_introspection_lists_root_and_types(): void
    {
        $this->seedSchema();
        $introspection = $this->graphql->introspect();

        self::assertArrayHasKey('__schema', $introspection);
        $schema = $introspection['__schema'];
        self::assertIsArray($schema);
        self::assertSame(['name' => 'Query'], $schema['queryType']);
        self::assertIsArray($schema['types']);
        $names = array_map(static fn (array $type): string => (string) $type['name'], $schema['types']);
        self::assertContains('Post', $names);
        self::assertContains('String', $names);
    }
}
