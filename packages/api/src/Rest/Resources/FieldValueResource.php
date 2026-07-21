<?php

declare(strict_types=1);

namespace OpenMeta\Api\Rest\Resources;

use OpenMeta\Fields\Field\Field;
use OpenMeta\Fields\Rest\FieldRestSerializer;

/**
 * Field value resource using `@openmeta/fields` REST Support serializer.
 */
final class FieldValueResource implements ResourceInterface
{
    public function __construct(
        private readonly Field $field,
        private readonly mixed $value,
        private readonly string $entityType,
        private readonly int|string $entityId,
        private readonly FieldRestSerializer $serializer = new FieldRestSerializer(),
    ) {
    }

    public function toArray(): array
    {
        $payload = $this->serializer->serialize($this->field, $this->value);
        $payload['entity_type'] = $this->entityType;
        $payload['entity_id'] = $this->entityId;

        return $payload;
    }
}
