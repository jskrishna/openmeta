<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Contracts;

use OpenMeta\Fields\Field\Field;
use OpenMeta\Validation\Results\ErrorBag;

/**
 * Lifecycle orchestrator — create, validate, save, load, delete with events.
 */
interface FieldManagerInterface
{
    public function create(FieldDefinitionInterface $definition): Field;

    public function validate(Field $field, mixed $value): ErrorBag;

    public function save(string $entityType, int|string $entityId, Field $field, mixed $value): void;

    public function load(string $entityType, int|string $entityId, Field $field): mixed;

    public function delete(string $entityType, int|string $entityId, Field $field): bool;
}
