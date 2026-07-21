<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Bootstrap;

/**
 * Outcome of a bootstrap pass: which extensions activated and which were
 * skipped (with the reasons why).
 */
final class BootstrapReport
{
    /**
     * @param list<string>               $activated Package ids activated, in load order
     * @param array<string, list<string>> $skipped   packageId => issues
     */
    public function __construct(
        public readonly array $activated = [],
        public readonly array $skipped = [],
    ) {
    }

    public function activatedCount(): int
    {
        return count($this->activated);
    }

    public function wasActivated(string $packageId): bool
    {
        return in_array($packageId, $this->activated, true);
    }
}
