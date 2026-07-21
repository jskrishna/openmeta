<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Contracts;

use OpenMeta\Fields\Field\Field;

/**
 * Builds configured field instances from types or definitions.
 */
interface FieldFactoryInterface
{
    /**
     * @param array<string, mixed> $settings
     */
    public function make(string $type, string $name, array $settings = []): Field;

    public function makeFromDefinition(FieldDefinitionInterface $definition): Field;
}
