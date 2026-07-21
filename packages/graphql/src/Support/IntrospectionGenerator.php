<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Support;

use OpenMeta\GraphQL\Interfaces\InterfaceType;
use OpenMeta\GraphQL\Schema\Schema;
use OpenMeta\GraphQL\Types\EnumType;
use OpenMeta\GraphQL\Types\FieldDefinition;
use OpenMeta\GraphQL\Types\ObjectType;

/**
 * Produces an introspection document (`__schema`) for an assembled schema.
 *
 * A pragmatic subset of the spec — enough for tooling to enumerate types,
 * fields, and root operations. Extensions may enrich descriptions via the
 * types they register.
 */
final class IntrospectionGenerator
{
    /**
     * @return array<string, mixed>
     */
    public function generate(Schema $schema): array
    {
        $registries = $schema->registries;
        $types = [];

        $types[] = $this->objectType($schema->queryType);
        if ($schema->mutationType !== null) {
            $types[] = $this->objectType($schema->mutationType);
        }

        foreach ($registries->types->all() as $object) {
            $types[] = $this->objectType($object);
        }

        foreach ($registries->interfaces->all() as $interface) {
            $types[] = $this->interfaceType($interface);
        }

        foreach ($registries->types->enums() as $enum) {
            $types[] = $this->enumType($enum);
        }

        foreach ($registries->scalars->all() as $scalar) {
            $types[] = ['kind' => 'SCALAR', 'name' => $scalar->name, 'description' => $scalar->description];
        }

        foreach ($registries->unions->all() as $union) {
            $types[] = [
                'kind' => 'UNION',
                'name' => $union->name,
                'possibleTypes' => array_map(
                    static fn (string $member): array => ['kind' => 'OBJECT', 'name' => $member],
                    $union->members,
                ),
            ];
        }

        return [
            '__schema' => [
                'queryType' => ['name' => 'Query'],
                'mutationType' => $schema->mutationType !== null ? ['name' => 'Mutation'] : null,
                'subscriptionType' => $schema->subscriptionTypeName !== null
                    ? ['name' => $schema->subscriptionTypeName]
                    : null,
                'types' => $types,
                'directives' => array_map(
                    static fn ($directive): array => [
                        'name' => $directive->name,
                        'locations' => $directive->locations,
                        'description' => $directive->description,
                    ],
                    $registries->directives->all(),
                ),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function objectType(ObjectType $type): array
    {
        return [
            'kind' => 'OBJECT',
            'name' => $type->name,
            'description' => $type->description,
            'interfaces' => array_map(
                static fn (string $name): array => ['kind' => 'INTERFACE', 'name' => $name],
                $type->interfaces,
            ),
            'fields' => array_map(fn (FieldDefinition $field): array => $this->field($field), $type->fields),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function interfaceType(InterfaceType $type): array
    {
        return [
            'kind' => 'INTERFACE',
            'name' => $type->name,
            'description' => $type->description,
            'fields' => array_map(fn (FieldDefinition $field): array => $this->field($field), $type->fields),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function enumType(EnumType $type): array
    {
        return [
            'kind' => 'ENUM',
            'name' => $type->name,
            'description' => $type->description,
            'enumValues' => array_map(
                static fn ($value): array => [
                    'name' => $value->name,
                    'isDeprecated' => $value->deprecationReason !== null,
                    'deprecationReason' => $value->deprecationReason,
                ],
                $type->values,
            ),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function field(FieldDefinition $field): array
    {
        return [
            'name' => $field->name,
            'description' => $field->description,
            'type' => ['name' => $field->type->baseName(), 'ofType' => $field->type->toSdl()],
            'args' => array_map(
                static fn ($arg): array => [
                    'name' => $arg->name,
                    'type' => ['name' => $arg->type->baseName(), 'ofType' => $arg->type->toSdl()],
                ],
                $field->args,
            ),
            'isDeprecated' => $field->isDeprecated(),
            'deprecationReason' => $field->deprecationReason,
        ];
    }
}
