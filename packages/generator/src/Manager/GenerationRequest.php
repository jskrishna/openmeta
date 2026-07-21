<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Manager;

/**
 * A request to run a named generator with a name and extra variables.
 */
final class GenerationRequest
{
    /**
     * @param array<string, mixed> $variables Extra template variables, merged over the resolved defaults
     */
    public function __construct(
        public readonly string $key,
        public readonly string $name,
        public readonly array $variables = [],
        public readonly GenerationOptions $options = new GenerationOptions(),
    ) {
    }
}
