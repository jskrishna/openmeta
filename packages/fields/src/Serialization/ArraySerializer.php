<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Serialization;

use OpenMeta\Fields\Contracts\FieldSerializerInterface;
use OpenMeta\Fields\Exceptions\SerializationFailureException;
use OpenMeta\Fields\Field\Field;

/**
 * Serializes field values to/from PHP arrays.
 */
final class ArraySerializer implements FieldSerializerInterface
{
    public function serialize(Field $field, mixed $value): mixed
    {
        $cast = $field->cast($field->sanitize($value));

        if (is_array($cast) || is_scalar($cast) || $cast === null) {
            return $cast;
        }

        if (is_object($cast) && method_exists($cast, 'toArray')) {
            /** @var array<string, mixed> $array */
            $array = $cast->toArray();

            return $array;
        }

        throw new SerializationFailureException(
            sprintf('Cannot serialize field [%s] value to array.', $field->name())
        );
    }

    public function deserialize(Field $field, mixed $payload): mixed
    {
        return $field->cast($payload);
    }
}
