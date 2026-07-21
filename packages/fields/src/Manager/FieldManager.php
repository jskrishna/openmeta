<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Manager;

use OpenMeta\Fields\Contracts\FieldDefinitionInterface;
use OpenMeta\Fields\Contracts\FieldManagerInterface;
use OpenMeta\Fields\Field\Field;
use OpenMeta\Fields\Lifecycle\FieldLifecycle;
use OpenMeta\Validation\Results\ErrorBag;

/**
 * Public lifecycle manager — create / validate / save / load / delete.
 */
final class FieldManager implements FieldManagerInterface
{
    public function __construct(
        private readonly FieldLifecycle $lifecycle,
    ) {
    }

    public function create(FieldDefinitionInterface $definition): Field
    {
        return $this->lifecycle->create($definition);
    }

    public function validate(Field $field, mixed $value): ErrorBag
    {
        return $this->lifecycle->validateValue($field, $value);
    }

    public function save(string $entityType, int|string $entityId, Field $field, mixed $value): void
    {
        $this->lifecycle->save($entityType, $entityId, $field, $value);
    }

    public function load(string $entityType, int|string $entityId, Field $field): mixed
    {
        return $this->lifecycle->load($entityType, $entityId, $field);
    }

    public function delete(string $entityType, int|string $entityId, Field $field): bool
    {
        return $this->lifecycle->delete($entityType, $entityId, $field);
    }

    public function lifecycle(): FieldLifecycle
    {
        return $this->lifecycle;
    }
}
