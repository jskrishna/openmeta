<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Registry;

use OpenMeta\Generator\Contracts\GeneratorInterface;
use OpenMeta\Generator\Contracts\NamespaceResolverInterface;
use OpenMeta\Generator\Contracts\PlaceholderResolverInterface;
use OpenMeta\Generator\Contracts\StubLoaderInterface;
use OpenMeta\Generator\Contracts\TemplateEngineInterface;

/**
 * Builds {@see TemplateGenerator}s from definitions, wiring the shared engine,
 * stub loader, and resolvers.
 */
final class GeneratorFactory
{
    public function __construct(
        private readonly StubLoaderInterface $stubs,
        private readonly TemplateEngineInterface $engine,
        private readonly PlaceholderResolverInterface $placeholders,
        private readonly NamespaceResolverInterface $namespaces,
    ) {
    }

    public function fromDefinition(GeneratorDefinition $definition): TemplateGenerator
    {
        return new TemplateGenerator(
            $definition,
            $this->stubs,
            $this->engine,
            $this->placeholders,
            $this->namespaces,
        );
    }

    /**
     * @return list<GeneratorInterface>
     */
    public function defaults(): array
    {
        return array_map(
            fn (GeneratorDefinition $definition): GeneratorInterface => $this->fromDefinition($definition),
            GeneratorDefinitions::all(),
        );
    }
}
