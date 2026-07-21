<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Types;

use OpenMeta\Fields\Field\Field;
use OpenMeta\Security\Sanitization\Sanitizer;

final class TextareaField extends Field
{
    public function type(): string
    {
        return 'textarea';
    }

    protected function typeRules(): array
    {
        $rules = ['string'];
        $max = $this->setting('max');

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

        return Sanitizer::text($value);
    }

    public function cast(mixed $value): mixed
    {
        return $value === null ? null : (string) $value;
    }
}
