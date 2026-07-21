<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Types;

use OpenMeta\Fields\Field\Field;
use OpenMeta\Security\Sanitization\Sanitizer;

/**
 * Image reference stub — media adapters land outside the Field Engine.
 */
final class ImageField extends Field
{
    public function type(): string
    {
        return 'image';
    }

    protected function typeRules(): array
    {
        return ['string'];
    }

    public function sanitize(mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        return Sanitizer::text($value);
    }

    public function cast(mixed $value): mixed
    {
        return $value === null || $value === '' ? null : (string) $value;
    }
}
