<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Discovery;

use OpenMeta\Sdk\Contracts\DiscoveryInterface;
use OpenMeta\Sdk\Contracts\ManifestInterface;

/**
 * Discovery source for manifests registered explicitly in code.
 */
final class ManualDiscovery implements DiscoveryInterface
{
    /** @var list<ManifestInterface> */
    private array $manifests = [];

    /**
     * @param list<ManifestInterface> $manifests
     */
    public function __construct(array $manifests = [])
    {
        foreach ($manifests as $manifest) {
            $this->add($manifest);
        }
    }

    public function add(ManifestInterface $manifest): void
    {
        $this->manifests[] = $manifest;
    }

    public function discover(): array
    {
        return $this->manifests;
    }
}
