<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Environment;

/**
 * A single environment diagnostic result.
 */
final class EnvironmentCheck
{
    public function __construct(
        public readonly string $name,
        public readonly bool $passed,
        public readonly string $message = '',
    ) {
    }
}
