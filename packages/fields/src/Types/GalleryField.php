<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Types;

use OpenMeta\Fields\Field\Field;
use OpenMeta\Security\Sanitization\Sanitizer;

/**
 * Gallery stores a list of image/file references (stub architecture).
 */
final class GalleryField extends Field
{
    public function type(): string
    {
        return 'gallery';
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

        if (! is_array($value)) {
            return [Sanitizer::text($value)];
        }

        return array_values(array_map(
            static fn (mixed $item): string => Sanitizer::text($item),
            $value
        ));
    }

    public function cast(mixed $value): mixed
    {
        return $this->sanitize($value);
    }
}
