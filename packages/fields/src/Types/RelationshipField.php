<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Types;

use OpenMeta\Fields\Field\Field;
use OpenMeta\Security\Sanitization\Sanitizer;

/**
 * Relationship stores related entity id(s). UI/canvas stays in Builder.
 */
final class RelationshipField extends Field
{
    public function type(): string
    {
        return 'relationship';
    }

    public function multiple(): bool
    {
        return (bool) $this->setting('multiple', false);
    }

    protected function typeRules(): array
    {
        return $this->multiple() ? ['array'] : ['integer'];
    }

    public function sanitize(mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($this->multiple()) {
            if (! is_array($value)) {
                return [Sanitizer::int($value)];
            }

            return array_values(array_map(
                static fn (mixed $id): int => Sanitizer::int($id),
                $value
            ));
        }

        return Sanitizer::int($value);
    }

    public function cast(mixed $value): mixed
    {
        return $this->sanitize($value);
    }
}
