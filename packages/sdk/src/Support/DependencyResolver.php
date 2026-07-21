<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Support;

use OpenMeta\Sdk\Contracts\DependencyResolverInterface;
use OpenMeta\Sdk\Contracts\ManifestInterface;
use OpenMeta\Sdk\Exceptions\DependencyException;

/**
 * Topological dependency resolver with circular-dependency detection.
 *
 * Optional dependencies that are absent from the set are ignored; required
 * dependencies that are absent raise a {@see DependencyException}.
 */
final class DependencyResolver implements DependencyResolverInterface
{
    public function resolve(array $manifests): array
    {
        /** @var array<string, ManifestInterface> $byId */
        $byId = [];

        foreach ($manifests as $manifest) {
            $byId[$manifest->packageId()] = $manifest;
        }

        /** @var list<ManifestInterface> $ordered */
        $ordered = [];
        /** @var array<string, bool> $resolved */
        $resolved = [];
        /** @var array<string, bool> $visiting */
        $visiting = [];

        foreach ($byId as $manifest) {
            $this->visit($manifest, $byId, $resolved, $visiting, $ordered, []);
        }

        return $ordered;
    }

    /**
     * @param array<string, ManifestInterface> $byId
     * @param array<string, bool>              $resolved
     * @param array<string, bool>              $visiting
     * @param list<ManifestInterface>          $ordered
     * @param list<string>                     $trail
     */
    private function visit(
        ManifestInterface $manifest,
        array $byId,
        array &$resolved,
        array &$visiting,
        array &$ordered,
        array $trail,
    ): void {
        $id = $manifest->packageId();

        if (isset($resolved[$id])) {
            return;
        }

        if (isset($visiting[$id])) {
            $trail[] = $id;
            throw DependencyException::circular($trail);
        }

        $visiting[$id] = true;
        $trail[] = $id;

        foreach ($manifest->dependencies() as $dependency) {
            $dependencyId = $dependency->packageId;

            if (! isset($byId[$dependencyId])) {
                if ($dependency->isRequired()) {
                    throw DependencyException::missing($id, $dependencyId);
                }

                continue;
            }

            $this->visit($byId[$dependencyId], $byId, $resolved, $visiting, $ordered, $trail);
        }

        unset($visiting[$id]);
        $resolved[$id] = true;
        $ordered[] = $manifest;
    }
}
