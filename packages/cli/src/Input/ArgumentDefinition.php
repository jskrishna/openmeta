<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Input;

/**
 * Declares a positional command argument.
 */
final class ArgumentDefinition
{
    public function __construct(
        public readonly string $name,
        public readonly bool $required = false,
        public readonly string $description = '',
        public readonly mixed $default = null,
    ) {
    }
}
