<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Registry;

use OpenMeta\Generator\Configuration\GeneratorConfiguration;
use OpenMeta\Generator\Contracts\GeneratorInterface;
use OpenMeta\Generator\Contracts\NamespaceResolverInterface;
use OpenMeta\Generator\Contracts\PlaceholderResolverInterface;
use OpenMeta\Generator\Contracts\StubLoaderInterface;
use OpenMeta\Generator\Contracts\TemplateEngineInterface;
use OpenMeta\Generator\Files\FileAction;
use OpenMeta\Generator\Files\GeneratedFile;
use OpenMeta\Generator\Manager\GenerationRequest;

/**
 * A generator driven entirely by a {@see GeneratorDefinition} + stub template.
 *
 * All 18 built-in generators are instances of this class; custom generators may
 * implement {@see GeneratorInterface} directly.
 */
final class TemplateGenerator implements GeneratorInterface
{
    public function __construct(
        private readonly GeneratorDefinition $definition,
        private readonly StubLoaderInterface $stubs,
        private readonly TemplateEngineInterface $engine,
        private readonly PlaceholderResolverInterface $placeholders,
        private readonly NamespaceResolverInterface $namespaces,
    ) {
    }

    public function key(): string
    {
        return $this->definition->key;
    }

    public function description(): string
    {
        return $this->definition->description;
    }

    public function generate(GenerationRequest $request, GeneratorConfiguration $config): array
    {
        $class = $this->className($request->name);
        $namespace = $this->namespaces->namespace($config, $this->definition->namespaceSuffix);

        $extra = array_merge(
            $this->definition->variables,
            ['namespace' => $namespace, 'class' => $class],
            $request->variables,
        );

        $variables = $this->placeholders->resolve($request->name, $extra, $config);
        $contents = $this->engine->render($this->stubs->load($this->definition->stub), $variables);

        $fqcn = $namespace === '' ? $class : $namespace . '\\' . $class;

        return [new GeneratedFile($this->path($config, $class), $contents, FileAction::Create, $fqcn)];
    }

    private function className(string $name): string
    {
        $class = $this->namespaces->className($name);
        $suffix = $this->definition->classSuffix;

        if ($suffix !== '' && ! str_ends_with($class, $suffix)) {
            $class .= $suffix;
        }

        return $class;
    }

    private function path(GeneratorConfiguration $config, string $class): string
    {
        $segments = array_filter(
            [rtrim($config->basePath, '/\\'), trim($this->definition->subdirectory, '/\\')],
            static fn (string $segment): bool => $segment !== '',
        );

        return implode('/', $segments) . '/' . $class . '.php';
    }
}
