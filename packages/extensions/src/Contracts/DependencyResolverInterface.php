<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Contracts;

use OpenMeta\Extensions\Exceptions\DependencyException;

/**
 * Orders extensions so that every dependency loads before its dependents.
 */
interface DependencyResolverInterface
{
    /**
     * @param list<ManifestInterface> $manifests
     *
     * @return list<ManifestInterface> Dependencies first, dependents last
     *
     * @throws DependencyException On a missing required dependency or a cycle
     */
    public function resolve(array $manifests): array;
}
