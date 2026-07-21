<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Types;

use OpenMeta\Fields\Field\Field;
use OpenMeta\Fields\Support\FieldSettings;
use OpenMeta\Security\Sanitization\Sanitizer;

final class SelectField extends Field
{
    public function type(): string
    {
        return 'select';
    }

    protected function typeRules(): array
    {
        $options = FieldSettings::optionValues($this->settings);
        $rules = ['string'];

        if ($options !== []) {
            $rules[] = 'in:' . implode(',', $options);
        }

        return $rules;
    }

    public function sanitize(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        return Sanitizer::text($value);
    }

    public function cast(mixed $value): mixed
    {
        return $value === null ? null : (string) $value;
    }
}
