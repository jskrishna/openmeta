<?php

declare(strict_types=1);

namespace OpenMeta\Builder\DragDrop;

use OpenMeta\Builder\Exceptions\BuilderException;
use OpenMeta\Fields\Registry\FieldRegistry;

/**
 * Validates drop operations before mutating canvas state.
 */
final class DropValidator
{
    public function __construct(private readonly FieldRegistry $fields)
    {
    }

    public function validate(DragSource $source, DropTarget $target): void
    {
        if (! $target->accepts($source->kind)) {
            throw new BuilderException(sprintf(
                'Drop target [%s] does not accept kind [%s].',
                $target->id,
                $source->kind
            ));
        }

        if ($source->kind === 'field-type' && $source->id !== '' && ! $this->fields->has($source->id)) {
            throw new BuilderException(sprintf('Cannot drop unknown field type [%s].', $source->id));
        }
    }
}
