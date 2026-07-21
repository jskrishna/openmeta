<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Factory;

use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Fields\Contracts\FieldDefinitionInterface;
use OpenMeta\Fields\Contracts\FieldFactoryInterface;
use OpenMeta\Fields\Events\FieldCreated;
use OpenMeta\Fields\Exceptions\InvalidDefinitionException;
use OpenMeta\Fields\Field\Field;
use OpenMeta\Fields\Registry\FieldRegistry;

/**
 * Builds field objects — resolves types, validates definitions, returns instances.
 */
final class FieldFactory implements FieldFactoryInterface
{
    public function __construct(
        private readonly FieldRegistry $registry,
        private readonly ?EventDispatcherInterface $events = null,
    ) {
    }

    public function make(string $type, string $name, array $settings = []): Field
    {
        if ($name === '') {
            throw new InvalidDefinitionException('Field name cannot be empty.');
        }

        $field = $this->registry->make($type, $name, $settings);
        $this->events?->dispatch(new FieldCreated($field));

        return $field;
    }

    public function makeFromDefinition(FieldDefinitionInterface $definition): Field
    {
        if ($definition->name() === '' || $definition->type() === '') {
            throw new InvalidDefinitionException('Definition must include name and type.');
        }

        return $this->make(
            $definition->type(),
            $definition->name(),
            $definition->toSettings()
        );
    }
}
