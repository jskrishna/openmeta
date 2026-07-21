<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Types;

use OpenMeta\Fields\Field\Field;
use OpenMeta\Security\Sanitization\Sanitizer;

final class TimeField extends Field
{
    public function type(): string
    {
        return 'time';
    }

    protected function typeRules(): array
    {
        return ['string'];
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
