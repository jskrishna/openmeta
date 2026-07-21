<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Contracts;

use OpenMeta\Fields\Field\Field;

/**
 * Storage-independent hydration — raw payloads → typed field values.
 */
interface FieldHydratorInterface
{
    public function hydrate(Field $field, mixed $raw): mixed;
}
