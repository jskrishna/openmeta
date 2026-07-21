<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Tests\Unit;

use OpenMeta\GraphQL\Directives\DirectiveRegistry;
use OpenMeta\GraphQL\Inputs\InputRegistry;
use OpenMeta\GraphQL\Interfaces\InterfaceRegistry;
use OpenMeta\GraphQL\Interfaces\InterfaceType;
use OpenMeta\GraphQL\Mutations\MutationRegistry;
use OpenMeta\GraphQL\Queries\QueryRegistry;
use OpenMeta\GraphQL\Resolvers\ResolverRegistry;
use OpenMeta\GraphQL\Scalars\ScalarRegistry;
use OpenMeta\GraphQL\Schema\SchemaBuilder;
use OpenMeta\GraphQL\Schema\SchemaRegistries;
use OpenMeta\GraphQL\Schema\SchemaValidator;
use OpenMeta\GraphQL\Types\FieldDefinition;
use OpenMeta\GraphQL\Types\ObjectType;
use OpenMeta\GraphQL\Types\TypeReference;
use OpenMeta\GraphQL\Types\TypeRegistry;
use OpenMeta\GraphQL\Unions\UnionRegistry;
use OpenMeta\GraphQL\Unions\UnionType;
use PHPUnit\Framework\TestCase;

final class SchemaValidatorTest extends TestCase
{
    private function registries(): SchemaRegistries
    {
        $scalars = new ScalarRegistry();
        $scalars->registerDefaults();

        return new SchemaRegistries(
            new TypeRegistry(),
            $scalars,
            new InputRegistry(),
            new InterfaceRegistry(),
            new UnionRegistry(),
            new DirectiveRegistry(),
            new QueryRegistry(),
            new MutationRegistry(),
            new ResolverRegistry(),
        );
    }

    public function test_valid_schema_has_no_errors(): void
    {
        $r = $this->registries();
        $r->queries->register(new FieldDefinition('ping', TypeReference::named('String')));

        $schema = (new SchemaBuilder())->build($r);

        self::assertSame([], (new SchemaValidator())->validate($schema));
    }

    public function test_empty_query_type_is_invalid(): void
    {
        $r = $this->registries();
        $schema = (new SchemaBuilder())->build($r);

        self::assertNotEmpty((new SchemaValidator())->validate($schema));
    }

    public function test_unknown_field_type_is_invalid(): void
    {
        $r = $this->registries();
        $r->queries->register(new FieldDefinition('post', TypeReference::named('Post')));

        $schema = (new SchemaBuilder())->build($r);
        $errors = (new SchemaValidator())->validate($schema);

        self::assertNotEmpty($errors);
        self::assertStringContainsString('Post', $errors[0]);
    }

    public function test_union_member_must_exist(): void
    {
        $r = $this->registries();
        $r->queries->register(new FieldDefinition('ping', TypeReference::named('String')));
        $r->unions->register(new UnionType('Content', ['Ghost']));

        $errors = (new SchemaValidator())->validate((new SchemaBuilder())->build($r));

        self::assertNotEmpty($errors);
    }

    public function test_interface_fields_must_be_implemented(): void
    {
        $r = $this->registries();
        $r->queries->register(new FieldDefinition('ping', TypeReference::named('String')));
        $r->interfaces->register(new InterfaceType('Node', [
            new FieldDefinition('id', TypeReference::named('ID')->nonNull()),
        ]));
        // Post claims to implement Node but omits `id`.
        $r->types->register(new ObjectType('Post', [
            new FieldDefinition('title', TypeReference::named('String')),
        ], '', ['Node']));

        $errors = (new SchemaValidator())->validate((new SchemaBuilder())->build($r));

        self::assertNotEmpty($errors);
    }
}
