<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Hydration;

use OpenMeta\Fields\Contracts\FieldHydratorInterface;
use OpenMeta\Fields\Field\Field;

/**
 * Generic hydration — storage-independent cast/sanitize of raw payloads.
 */
final class FieldHydrator implements FieldHydratorInterface
{
    public function hydrate(Field $field, mixed $raw): mixed
    {
        if ($raw === null) {
            $default = $field->setting('default');

            if ($default !== null) {
                return $field->cast($field->sanitize($default));
            }

            return null;
        }

        return $field->cast($field->sanitize($raw));
    }

    /**
     * @param array<string, Field> $fields
     * @param array<string, mixed> $raw
     * @return array<string, mixed>
     */
    public function hydrateMany(array $fields, array $raw): array
    {
        $out = [];

        foreach ($fields as $name => $field) {
            $out[$name] = $this->hydrate($field, $raw[$name] ?? null);
        }

        return $out;
    }
}
