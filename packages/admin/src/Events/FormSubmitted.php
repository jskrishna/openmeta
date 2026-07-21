<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Events;

final class FormSubmitted
{
    /**
     * @param array<string, mixed> $values
     */
    public function __construct(
        public readonly string $formId,
        public readonly bool $success,
        public readonly array $values = [],
    ) {
    }
}
