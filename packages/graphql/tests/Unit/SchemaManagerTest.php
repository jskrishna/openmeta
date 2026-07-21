<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Tests\Unit;

use OpenMeta\GraphQL\Contracts\SchemaExtensionInterface;
use OpenMeta\GraphQL\Errors\SchemaException;
use OpenMeta\GraphQL\Events\SchemaBuilt;
use OpenMeta\GraphQL\Schema\SchemaRegistries;
use OpenMeta\GraphQL\Tests\GraphQLTestCase;
use OpenMeta\GraphQL\Types\FieldDefinition;
use OpenMeta\GraphQL\Types\TypeReference;

final class SchemaManagerTest extends GraphQLTestCase
{
    public function test_builds_and_versions_schema(): void
    {
        $this->query(new FieldDefinition('ping', TypeReference::named('String')));

        /** @var list<object> $built */
        $built = [];
        $this->capture(SchemaBuilt::class, $built);

        $schema = $this->schemaManager->schema();

        self::assertSame('Query', $schema->queryType->name);
        self::assertSame('v1', $this->schemaManager->version());
        self::assertCount(1, $built);
    }

    public function test_invalid_schema_throws(): void
    {
        $this->query(new FieldDefinition('post', TypeReference::named('DoesNotExist')));

        $this->expectException(SchemaException::class);
        $this->schemaManager->schema();
    }

    public function test_extension_contributes_and_rebuilds(): void
    {
        $this->query(new FieldDefinition('ping', TypeReference::named('String')));
        $this->schemaManager->schema();

        $this->schemaManager->extend(new class implements SchemaExtensionInterface {
            public function extend(SchemaRegistries $registries): void
            {
                $registries->queries->register(new FieldDefinition('pong', TypeReference::named('String')));
            }
        });

        $schema = $this->schemaManager->schema();

        self::assertNotNull($schema->queryType->field('pong'));
        self::assertSame('v2', $this->schemaManager->version());
    }
}
