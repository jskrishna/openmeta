<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Types;

use OpenMeta\Fields\Field\Field;

/**
 * Nested group of sub-values (array map). Sub-field engines expand later.
 */
final class GroupField extends Field
{
    public function type(): string
    {
        return 'group';
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

        return is_array($value) ? $value : [];
    }

    public function cast(mixed $value): mixed
    {
        return $this->sanitize($value);
    }
}
