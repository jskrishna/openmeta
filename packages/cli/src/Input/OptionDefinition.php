<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Input;

/**
 * Declares a command option (`--name` / `--name=value`, optional `-s` shortcut).
 *
 * When {@see $acceptsValue} is false the option is a boolean flag.
 */
final class OptionDefinition
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $shortcut = null,
        public readonly bool $acceptsValue = false,
        public readonly string $description = '',
        public readonly mixed $default = null,
    ) {
    }
}
