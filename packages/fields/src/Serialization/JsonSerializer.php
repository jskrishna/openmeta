<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Serialization;

use OpenMeta\Fields\Contracts\FieldSerializerInterface;
use OpenMeta\Fields\Exceptions\SerializationFailureException;
use OpenMeta\Fields\Field\Field;
use JsonException;

/**
 * JSON string serializer — pluggable via SerializerRegistry.
 */
final class JsonSerializer implements FieldSerializerInterface
{
    public function __construct(
        private readonly ArraySerializer $arrays = new ArraySerializer(),
    ) {
    }

    public function serialize(Field $field, mixed $value): string
    {
        try {
            return json_encode(
                $this->arrays->serialize($field, $value),
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException $e) {
            throw new SerializationFailureException(
                sprintf('JSON serialize failed for field [%s].', $field->name()),
                0,
                $e
            );
        }
    }

    public function deserialize(Field $field, mixed $payload): mixed
    {
        if ($payload === null || $payload === '') {
            return null;
        }

        if (! is_string($payload)) {
            return $this->arrays->deserialize($field, $payload);
        }

        try {
            $decoded = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new SerializationFailureException(
                sprintf('JSON deserialize failed for field [%s].', $field->name()),
                0,
                $e
            );
        }

        return $this->arrays->deserialize($field, $decoded);
    }
}
