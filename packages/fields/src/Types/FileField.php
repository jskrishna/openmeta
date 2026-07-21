<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Types;

use OpenMeta\Fields\Field\Field;
use OpenMeta\Security\Sanitization\Sanitizer;

/**
 * File reference stub — media adapters land in Wordpress / storage adapters.
 */
final class FileField extends Field
{
    public function type(): string
    {
        return 'file';
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
