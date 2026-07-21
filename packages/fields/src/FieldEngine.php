<?php

declare(strict_types=1);

namespace OpenMeta\Fields;

use OpenMeta\Fields\Contracts\FieldEngineInterface;
use OpenMeta\Fields\Contracts\FieldFactoryInterface;
use OpenMeta\Fields\Contracts\FieldManagerInterface;
use OpenMeta\Fields\Contracts\FieldRendererInterface;
use OpenMeta\Fields\Exceptions\FieldException;
use OpenMeta\Fields\Exceptions\ValidationFailedException;
use OpenMeta\Fields\Field\Field;
use OpenMeta\Fields\Registry\FieldRegistry;
use OpenMeta\Validation\Results\ErrorBag;

/**
 * OpenMeta field engine façade — register, validate, save, load, render.
 *
 * Public API surface: Registry, Factory, Manager (+ this façade).
 */
final class FieldEngine implements FieldEngineInterface
{
    public function __construct(
        private readonly FieldRegistry $registry,
        private readonly FieldFactoryInterface $factory,
        private readonly FieldManagerInterface $manager,
        private readonly FieldRendererInterface $renderer,
    ) {
    }

    public function register(string $type, string|callable $factory, ?string $version = null): void
    {
        $this->registry->register($type, $factory, $version);
    }

    public function make(string $type, string $name, array $settings = []): Field
    {
        return $this->factory->make($type, $name, $settings);
    }

    public function validate(Field $field, mixed $value): ErrorBag
    {
        return $this->manager->validate($field, $value);
    }

    public function save(string $entityType, int|string $entityId, Field $field, mixed $value): void
    {
        try {
            $this->manager->save($entityType, $entityId, $field, $value);
        } catch (ValidationFailedException $e) {
            throw new FieldException($e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    public function load(string $entityType, int|string $entityId, Field $field): mixed
    {
        return $this->manager->load($entityType, $entityId, $field);
    }

    public function delete(string $entityType, int|string $entityId, Field $field): bool
    {
        return $this->manager->delete($entityType, $entityId, $field);
    }

    public function render(Field $field, mixed $value, string $context = 'edit'): string
    {
        return $this->renderer->render($field, $value, $context);
    }

    public function display(Field $field, mixed $value): string
    {
        return $this->renderer->display($field, $value);
    }

    public function registry(): FieldRegistry
    {
        return $this->registry;
    }

    public function factory(): FieldFactoryInterface
    {
        return $this->factory;
    }

    public function manager(): FieldManagerInterface
    {
        return $this->manager;
    }
}
