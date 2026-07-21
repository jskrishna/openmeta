<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Validation;

use OpenMeta\Fields\Field\Field;
use OpenMeta\Validation\Results\ErrorBag;
use OpenMeta\Validation\Validation;

/**
 * Bridges field values to `@openmeta/validation` — no second engine.
 */
final class FieldValidator
{
    public function validate(Field $field, mixed $value): ErrorBag
    {
        $validator = Validation::make(
            [$field->name() => $value],
            [$field->name() => $field->rules()]
        );

        return $validator->errors();
    }

    public function passes(Field $field, mixed $value): bool
    {
        return $this->validate($field, $value)->isEmpty();
    }
}
