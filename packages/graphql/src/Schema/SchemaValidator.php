<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Schema;

use OpenMeta\GraphQL\Types\FieldDefinition;
use OpenMeta\GraphQL\Types\ObjectType;

/**
 * Validates an assembled schema: every referenced type must resolve, unions
 * must reference known members, and object types must satisfy the interfaces
 * they declare.
 */
final class SchemaValidator
{
    /**
     * @return list<string> Human-readable validation errors (empty when valid)
     */
    public function validate(Schema $schema): array
    {
        $registries = $schema->registries;
        $errors = [];

        if ($schema->queryType->fields === []) {
            $errors[] = 'The Query type must define at least one field.';
        }

        $this->validateFields('Query', $schema->queryType->fields, $registries, $errors);

        if ($schema->mutationType !== null) {
            $this->validateFields('Mutation', $schema->mutationType->fields, $registries, $errors);
        }

        foreach ($registries->types->all() as $object) {
            $this->validateFields($object->name, $object->fields, $registries, $errors);
            $this->validateInterfaces($object, $registries, $errors);
        }

        foreach ($registries->interfaces->all() as $interface) {
            $this->validateFields($interface->name, $interface->fields, $registries, $errors);
        }

        foreach ($registries->inputs->all() as $input) {
            foreach ($input->fields as $field) {
                if (! $registries->isKnownType($field->type->baseName())) {
                    $errors[] = sprintf(
                        'Input [%s.%s] references unknown type [%s].',
                        $input->name,
                        $field->name,
                        $field->type->baseName(),
                    );
                }
            }
        }

        foreach ($registries->unions->all() as $union) {
            foreach ($union->members as $member) {
                if (! $registries->types->has($member)) {
                    $errors[] = sprintf('Union [%s] references unknown member [%s].', $union->name, $member);
                }
            }
        }

        return $errors;
    }

    /**
     * @param list<FieldDefinition> $fields
     * @param list<string>          $errors
     */
    private function validateFields(string $owner, array $fields, SchemaRegistries $registries, array &$errors): void
    {
        foreach ($fields as $field) {
            if (! $registries->isKnownType($field->type->baseName())) {
                $errors[] = sprintf(
                    'Field [%s.%s] references unknown type [%s].',
                    $owner,
                    $field->name,
                    $field->type->baseName(),
                );
            }

            foreach ($field->args as $arg) {
                if (! $registries->isKnownType($arg->type->baseName())) {
                    $errors[] = sprintf(
                        'Argument [%s.%s(%s)] references unknown type [%s].',
                        $owner,
                        $field->name,
                        $arg->name,
                        $arg->type->baseName(),
                    );
                }
            }
        }
    }

    /**
     * @param list<string> $errors
     */
    private function validateInterfaces(ObjectType $object, SchemaRegistries $registries, array &$errors): void
    {
        foreach ($object->interfaces as $interfaceName) {
            if (! $registries->interfaces->has($interfaceName)) {
                $errors[] = sprintf('Type [%s] implements unknown interface [%s].', $object->name, $interfaceName);

                continue;
            }

            $required = $registries->interfaces->get($interfaceName)->fieldNames();
            $present = $object->fieldNames();

            foreach ($required as $fieldName) {
                if (! in_array($fieldName, $present, true)) {
                    $errors[] = sprintf(
                        'Type [%s] is missing field [%s] required by interface [%s].',
                        $object->name,
                        $fieldName,
                        $interfaceName,
                    );
                }
            }
        }
    }
}
