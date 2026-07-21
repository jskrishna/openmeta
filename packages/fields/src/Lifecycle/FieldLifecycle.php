<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Lifecycle;

use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Fields\Contracts\FieldDefinitionInterface;
use OpenMeta\Fields\Contracts\FieldHydratorInterface;
use OpenMeta\Fields\Contracts\FieldSerializerInterface;
use OpenMeta\Fields\Contracts\FieldStorageInterface;
use OpenMeta\Fields\Events\FieldDeleted;
use OpenMeta\Fields\Events\FieldLoaded;
use OpenMeta\Fields\Events\FieldSaved;
use OpenMeta\Fields\Events\FieldValidated;
use OpenMeta\Fields\Exceptions\InvalidDefinitionException;
use OpenMeta\Fields\Exceptions\ValidationFailedException;
use OpenMeta\Fields\Factory\FieldFactory;
use OpenMeta\Fields\Field\Field;
use OpenMeta\Fields\Validation\FieldValidator;
use OpenMeta\Validation\Results\ErrorBag;

/**
 * Orchestrates the full field lifecycle spine.
 *
 * Register → Build Definition → Validate Configuration → Hydrate → Render
 * → Validate Value → Serialize → Store → Load → Deserialize → Return
 */
final class FieldLifecycle
{
    public function __construct(
        private readonly FieldFactory $factory,
        private readonly FieldValidator $validator,
        private readonly FieldStorageInterface $storage,
        private readonly FieldHydratorInterface $hydrator,
        private readonly FieldSerializerInterface $serializer,
        private readonly EventDispatcherInterface $events,
    ) {
    }

    public function create(FieldDefinitionInterface $definition): Field
    {
        $this->validateConfiguration($definition);

        return $this->factory->makeFromDefinition($definition);
    }

    public function validateConfiguration(FieldDefinitionInterface $definition): void
    {
        if ($definition->name() === '' || $definition->type() === '') {
            throw new InvalidDefinitionException('Definition name and type are required.');
        }
    }

    public function validateValue(Field $field, mixed $value): ErrorBag
    {
        $errors = $this->validator->validate($field, $value);
        $this->events->dispatch(new FieldValidated($field, $value, $errors));

        return $errors;
    }

    public function save(string $entityType, int|string $entityId, Field $field, mixed $value): void
    {
        $errors = $this->validateValue($field, $value);

        if (! $errors->isEmpty()) {
            throw new ValidationFailedException(
                sprintf('Cannot save field [%s]: %s', $field->name(), (string) $errors->first()),
                $errors
            );
        }

        $serialized = $this->serializer->serialize($field, $value);
        $this->storage->save($entityType, $entityId, $field, $serialized);
        $this->events->dispatch(new FieldSaved($entityType, $entityId, $field, $serialized));
    }

    public function load(string $entityType, int|string $entityId, Field $field): mixed
    {
        $raw = $this->storage->load($entityType, $entityId, $field);
        $deserialized = $this->serializer->deserialize($field, $raw);
        $value = $this->hydrator->hydrate($field, $deserialized);
        $this->events->dispatch(new FieldLoaded($entityType, $entityId, $field, $value));

        return $value;
    }

    public function delete(string $entityType, int|string $entityId, Field $field): bool
    {
        $deleted = $this->storage->delete($entityType, $entityId, $field);

        if ($deleted) {
            $this->events->dispatch(new FieldDeleted($entityType, $entityId, $field));
        }

        return $deleted;
    }

    /**
     * @return list<string>
     */
    public static function phases(): array
    {
        return array_map(
            static fn (FieldLifecyclePhase $p): string => $p->value,
            FieldLifecyclePhase::cases()
        );
    }
}
