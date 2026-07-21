<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Support;

use OpenMeta\GraphQL\Interfaces\InterfaceType;
use OpenMeta\GraphQL\Inputs\InputType;
use OpenMeta\GraphQL\Schema\Schema;
use OpenMeta\GraphQL\Types\EnumType;
use OpenMeta\GraphQL\Types\ObjectType;

/**
 * Generates a GraphQL SDL document from an assembled schema.
 */
final class SchemaPrinter
{
    public function print(Schema $schema): string
    {
        $registries = $schema->registries;
        $blocks = [];

        $operations = ['  query: Query'];
        if ($schema->hasMutations()) {
            $operations[] = '  mutation: Mutation';
        }
        $blocks[] = "schema {\n" . implode("\n", $operations) . "\n}";

        foreach ($registries->scalars->all() as $scalar) {
            if (! $registries->scalars->isBuiltIn($scalar->name)) {
                $blocks[] = 'scalar ' . $scalar->name;
            }
        }

        foreach ($registries->interfaces->all() as $interface) {
            $blocks[] = $this->printInterface($interface);
        }

        $blocks[] = $this->printObject($schema->queryType);
        if ($schema->mutationType !== null) {
            $blocks[] = $this->printObject($schema->mutationType);
        }

        foreach ($registries->types->all() as $object) {
            $blocks[] = $this->printObject($object);
        }

        foreach ($registries->types->enums() as $enum) {
            $blocks[] = $this->printEnum($enum);
        }

        foreach ($registries->unions->all() as $union) {
            $blocks[] = sprintf('union %s = %s', $union->name, implode(' | ', $union->members));
        }

        foreach ($registries->inputs->all() as $input) {
            $blocks[] = $this->printInput($input);
        }

        return implode("\n\n", $blocks) . "\n";
    }

    private function printObject(ObjectType $type): string
    {
        $implements = $type->interfaces === []
            ? ''
            : ' implements ' . implode(' & ', $type->interfaces);

        $fields = array_map(
            static fn ($field): string => '  ' . $field->toSdl(),
            $type->fields,
        );

        return sprintf("type %s%s {\n%s\n}", $type->name, $implements, implode("\n", $fields));
    }

    private function printInterface(InterfaceType $type): string
    {
        $fields = array_map(
            static fn ($field): string => '  ' . $field->toSdl(),
            $type->fields,
        );

        return sprintf("interface %s {\n%s\n}", $type->name, implode("\n", $fields));
    }

    private function printInput(InputType $type): string
    {
        $fields = array_map(
            static fn ($field): string => '  ' . $field->toSdl(),
            $type->fields,
        );

        return sprintf("input %s {\n%s\n}", $type->name, implode("\n", $fields));
    }

    private function printEnum(EnumType $type): string
    {
        $values = array_map(
            static fn ($value): string => '  ' . $value->name,
            $type->values,
        );

        return sprintf("enum %s {\n%s\n}", $type->name, implode("\n", $values));
    }
}
