<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Registry;

/**
 * Declarative description of a template-driven generator: which stub to render,
 * where the file lands, and its namespace/class conventions.
 *
 * Adding a new generator type is data, not code — register another definition.
 */
final class GeneratorDefinition
{
    /**
     * @param array<string, string> $variables Extra template variables (e.g. extends, baseImport)
     */
    public function __construct(
        public readonly string $key,
        public readonly string $description,
        public readonly string $stub,
        public readonly string $subdirectory,
        public readonly string $namespaceSuffix,
        public readonly string $classSuffix = '',
        public readonly array $variables = [],
    ) {
    }
}
