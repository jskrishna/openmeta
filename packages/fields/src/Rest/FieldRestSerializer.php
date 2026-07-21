<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Rest;

use OpenMeta\Fields\Field\Field;

/**
 * Field REST exposure contracts (serializers). Route registration stays in `@openmeta/api`.
 */
final class FieldRestSerializer
{
    /**
     * @return array{type: string, name: string, label: string, settings: array<string, mixed>, value: mixed}
     */
    public function serialize(Field $field, mixed $value): array
    {
        return [
            'type' => $field->type(),
            'name' => $field->name(),
            'label' => $field->label(),
            'settings' => $field->settings(),
            'value' => $field->cast($value),
        ];
    }
}
