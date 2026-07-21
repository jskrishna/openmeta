<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Resolvers;

use OpenMeta\Generator\Configuration\GeneratorConfiguration;
use OpenMeta\Generator\Contracts\NamespaceResolverInterface;
use OpenMeta\Support\Str\Str;

/**
 * Resolves target namespaces/class names and flags reserved namespaces.
 */
final class NamespaceResolver implements NamespaceResolverInterface
{
    public function namespace(GeneratorConfiguration $config, string $suffix): string
    {
        $base = trim($config->baseNamespace, '\\');
        $suffix = trim($suffix, '\\');

        return $suffix === '' ? $base : $base . '\\' . $suffix;
    }

    public function className(string $name): string
    {
        return Str::studly($name);
    }

    public function isReserved(GeneratorConfiguration $config, string $namespace): bool
    {
        $namespace = trim($namespace, '\\');

        foreach ($config->reservedNamespaces as $reserved) {
            $reserved = trim($reserved, '\\');

            if ($namespace === $reserved || str_starts_with($namespace, $reserved . '\\')) {
                return true;
            }
        }

        return false;
    }
}
