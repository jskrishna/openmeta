<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Types;

use OpenMeta\Fields\Field\Field;
use OpenMeta\Security\Sanitization\Sanitizer;

final class NumberField extends Field
{
    public function type(): string
    {
        return 'number';
    }

    protected function typeRules(): array
    {
        $rules = ['numeric'];
        $min = $this->setting('min');
        $max = $this->setting('max');

        if (is_numeric($min)) {
            $rules[] = 'min:' . $min;
        }

        if (is_numeric($max)) {
            $rules[] = 'max:' . $max;
        }

        return $rules;
    }

    public function sanitize(mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        return Sanitizer::float($value);
    }

    public function cast(mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        return is_numeric($value) ? $value + 0 : $value;
    }
}
