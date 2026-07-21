<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Discovery;

use OpenMeta\Sdk\Contracts\DiscoveryInterface;

/**
 * Aggregates several discovery sources and de-duplicates by package id.
 *
 * Earlier sources win on conflict, so callers can layer manual overrides
 * ahead of automatic discovery.
 */
final class DiscoveryManager implements DiscoveryInterface
{
    /** @var list<DiscoveryInterface> */
    private array $sources = [];

    /**
     * @param list<DiscoveryInterface> $sources
     */
    public function __construct(array $sources = [])
    {
        foreach ($sources as $source) {
            $this->addSource($source);
        }
    }

    public function addSource(DiscoveryInterface $source): void
    {
        $this->sources[] = $source;
    }

    public function discover(): array
    {
        /** @var array<string, \OpenMeta\Sdk\Contracts\ManifestInterface> $unique */
        $unique = [];

        foreach ($this->sources as $source) {
            foreach ($source->discover() as $manifest) {
                $unique[$manifest->packageId()] ??= $manifest;
            }
        }

        return array_values($unique);
    }
}
