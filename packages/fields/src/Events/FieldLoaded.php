<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Events;

use OpenMeta\Fields\Field\Field;

/**
 * Dispatched after a field value is loaded from storage.
 */
final class FieldLoaded
{
    public function __construct(
        public readonly string $entityType,
        public readonly int|string $entityId,
        public readonly Field $field,
        public readonly mixed $value,
    ) {
    }
}
