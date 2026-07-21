<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Contracts;

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
