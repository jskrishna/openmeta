<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Types;

use OpenMeta\Fields\Field\Field;

/**
 * Free-form object / associative array value.
 */
final class ObjectField extends Field
{
    public function type(): string
    {
        return 'object';
    }

    protected function typeRules(): array
    {
        return ['array'];
    }

    public function sanitize(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        if (is_object($value)) {
            return (array) $value;
        }

        return is_array($value) ? $value : [];
    }

    public function cast(mixed $value): mixed
    {
        return $this->sanitize($value);
    }
}
