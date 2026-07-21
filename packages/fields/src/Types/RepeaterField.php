<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Types;

use OpenMeta\Fields\Field\Field;

/**
 * Repeater stores a list of row arrays. Sub-field engines land in later milestones.
 */
final class RepeaterField extends Field
{
    public function type(): string
    {
        return 'repeater';
    }

    protected function typeRules(): array
    {
        $rules = ['array'];
        $min = $this->setting('min');
        $max = $this->setting('max');

        if (is_numeric($min)) {
            $rules[] = 'min:' . (int) $min;
        }

        if (is_numeric($max)) {
            $rules[] = 'max:' . (int) $max;
        }

        return $rules;
    }

    public function sanitize(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        return is_array($value) ? array_values($value) : [];
    }

    public function cast(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        return is_array($value) ? array_values($value) : [];
    }
}
