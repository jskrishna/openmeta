<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Contracts;

use OpenMeta\Fields\Field\Field;
use OpenMeta\Validation\Results\ErrorBag;

/**
 * Heart of OpenMeta — register, validate, save, load, render.
 */
interface FieldEngineInterface
{
    /**
     * @param class-string<Field>|callable(string, array<string, mixed>): Field $factory
     */
    public function register(string $type, string|callable $factory, ?string $version = null): void;

    /** @param array<string, mixed> $settings */
    public function make(string $type, string $name, array $settings = []): Field;

    public function validate(Field $field, mixed $value): ErrorBag;

    public function save(string $entityType, int|string $entityId, Field $field, mixed $value): void;

    public function load(string $entityType, int|string $entityId, Field $field): mixed;

    public function render(Field $field, mixed $value, string $context = 'edit'): string;

    public function display(Field $field, mixed $value): string;
}
