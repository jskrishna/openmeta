<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Events;

use OpenMeta\Fields\Field\Field;
use OpenMeta\Validation\Results\ErrorBag;

/**
 * Dispatched after field value validation runs.
 */
final class FieldValidated
{
    public function __construct(
        public readonly Field $field,
        public readonly mixed $value,
        public readonly ErrorBag $errors,
    ) {
    }
}
