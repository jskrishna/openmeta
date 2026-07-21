<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Events;

use OpenMeta\Fields\Field\Field;

/**
 * Dispatched when a field instance is created via factory/manager.
 */
final class FieldCreated
{
    public function __construct(
        public readonly Field $field,
    ) {
    }
}
