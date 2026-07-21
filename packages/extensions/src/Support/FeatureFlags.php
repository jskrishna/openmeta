<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Support;

use OpenMeta\Extensions\Contracts\FeatureFlagsInterface;

/**
 * Default in-memory feature flags.
 *
 * Flag names are namespaced by convention as "packageId:flag" when seeded
 * from a manifest, avoiding collisions between extensions.
 */
final class FeatureFlags implements FeatureFlagsInterface
{
    /** @var array<string, bool> */
    private array $flags;

    /**
     * @param array<string, bool> $flags
     */
    public function __construct(array $flags = [])
    {
        $this->flags = $flags;
    }

    public function enable(string $flag): void
    {
        $this->flags[$flag] = true;
    }

    public function disable(string $flag): void
    {
        $this->flags[$flag] = false;
    }

    public function isEnabled(string $flag): bool
    {
        return $this->flags[$flag] ?? false;
    }

    /**
     * Seed flags from a manifest, namespaced by the package id.
     *
     * @param array<string, bool> $flags
     */
    public function seed(string $packageId, array $flags): void
    {
        foreach ($flags as $name => $enabled) {
            $this->flags[$packageId . ':' . $name] = $enabled;
        }
    }

    public function all(): array
    {
        return $this->flags;
    }
}
