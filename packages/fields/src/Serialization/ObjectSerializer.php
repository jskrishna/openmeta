<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Serialization;

use OpenMeta\Fields\Contracts\FieldSerializerInterface;
use OpenMeta\Fields\Exceptions\SerializationFailureException;
use OpenMeta\Fields\Field\Field;
use stdClass;

/**
 * Object serializer — values become stdClass (or stay objects).
 */
final class ObjectSerializer implements FieldSerializerInterface
{
    public function __construct(
        private readonly ArraySerializer $arrays = new ArraySerializer(),
    ) {
    }

    public function serialize(Field $field, mixed $value): object
    {
        $array = $this->arrays->serialize($field, $value);

        if (is_object($array)) {
            return $array;
        }

        if (is_array($array)) {
            return (object) $array;
        }

        $object = new stdClass();
        $object->value = $array;

        return $object;
    }

    public function deserialize(Field $field, mixed $payload): mixed
    {
        if ($payload instanceof stdClass) {
            $vars = get_object_vars($payload);

            if (array_keys($vars) === ['value']) {
                return $this->arrays->deserialize($field, $vars['value']);
            }

            return $this->arrays->deserialize($field, $vars);
        }

        if (is_object($payload)) {
            throw new SerializationFailureException(
                sprintf('Unsupported object payload for field [%s].', $field->name())
            );
        }

        return $this->arrays->deserialize($field, $payload);
    }
}
