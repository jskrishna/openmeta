<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Configuration;

/**
 * Project conventions the generators follow: base namespace/path, author and
 * license header, template variables, stub search paths, and reserved
 * (framework-owned) namespaces that may never be generated into.
 */
final class GeneratorConfiguration
{
    /**
     * @param list<string> $stubPaths           Directories searched for stub templates
     * @param list<string> $reservedNamespaces  Namespace prefixes that must not be generated into
     */
    public function __construct(
        public readonly string $baseNamespace = 'App',
        public readonly string $basePath = 'src',
        public readonly string $author = '',
        public readonly string $license = '',
        public readonly string $year = '',
        public readonly array $stubPaths = [],
        public readonly array $reservedNamespaces = ['OpenMeta'],
    ) {
    }

    public function withStubPath(string $path): self
    {
        return new self(
            $this->baseNamespace,
            $this->basePath,
            $this->author,
            $this->license,
            $this->year,
            [...$this->stubPaths, $path],
            $this->reservedNamespaces,
        );
    }
}
