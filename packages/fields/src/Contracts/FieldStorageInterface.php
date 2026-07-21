<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Contracts;

use OpenMeta\Fields\Field\Field;

/**
 * Storage adapter contract — WordPress meta / custom tables / JSON / external.
 *
 * Implementations live behind this interface; Fields never call `$wpdb` directly.
 */
interface FieldStorageInterface
{
    public function save(string $entityType, int|string $entityId, Field $field, mixed $value): void;

    public function load(string $entityType, int|string $entityId, Field $field): mixed;

    public function delete(string $entityType, int|string $entityId, Field $field): bool;
}
