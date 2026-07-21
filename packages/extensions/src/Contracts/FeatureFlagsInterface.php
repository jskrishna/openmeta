<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Contracts;

/**
 * A simple on/off feature flag store, seeded from extension manifests.
 */
interface FeatureFlagsInterface
{
    public function enable(string $flag): void;

    public function disable(string $flag): void;

    public function isEnabled(string $flag): bool;

    /**
     * @return array<string, bool>
     */
    public function all(): array;
}
