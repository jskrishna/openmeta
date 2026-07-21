<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Contracts;

use OpenMeta\Fields\Field\Field;

/**
 * Rendering contract — Admin / UI packages supply concrete HTML/component renderers.
 *
 * The Field Engine ships a safe default string renderer for tests and non-UI contexts.
 */
interface FieldRendererInterface
{
    public function render(Field $field, mixed $value, string $context = 'edit'): string;

    public function display(Field $field, mixed $value): string;
}
