<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Events;

use OpenMeta\Fields\Field\Field;

/**
 * Dispatched after a field value is deleted from storage.
 */
final class FieldDeleted
{
    public function __construct(
        public readonly string $entityType,
        public readonly int|string $entityId,
        public readonly Field $field,
    ) {
    }
}
