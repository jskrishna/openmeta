<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Contracts;

use OpenMeta\Generator\Configuration\GeneratorConfiguration;

/**
 * Resolves target namespaces and class names from project conventions.
 */
interface NamespaceResolverInterface
{
    public function namespace(GeneratorConfiguration $config, string $suffix): string;

    public function className(string $name): string;

    public function isReserved(GeneratorConfiguration $config, string $namespace): bool;
}
