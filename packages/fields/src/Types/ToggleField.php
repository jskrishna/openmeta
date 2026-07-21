<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Types;

use OpenMeta\Fields\Field\Field;
use OpenMeta\Security\Sanitization\Sanitizer;

final class ToggleField extends Field
{
    public function type(): string
    {
        return 'toggle';
    }

    protected function typeRules(): array
    {
        return ['boolean'];
    }

    public function sanitize(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        return Sanitizer::bool($value);
    }

    public function cast(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        return (bool) $value;
    }
}
