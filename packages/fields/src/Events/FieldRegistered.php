<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Events;

use OpenMeta\Fields\Field\Field;

/**
 * Dispatched when a field type is registered on the registry.
 */
final class FieldRegistered
{
    public function __construct(
        public readonly string $type,
        public readonly string|null $version = null,
    ) {
    }
}
