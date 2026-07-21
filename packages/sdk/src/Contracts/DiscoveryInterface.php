<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Contracts;

/**
 * A source of extension manifests.
 */
interface DiscoveryInterface
{
    /**
     * @return list<ManifestInterface>
     */
    public function discover(): array;
}
