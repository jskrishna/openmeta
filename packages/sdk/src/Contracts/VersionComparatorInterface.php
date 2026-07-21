<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Contracts;

/**
 * Semantic version comparison contract (ADR-0019).
 */
interface VersionComparatorInterface
{
    /**
     * Compare two versions: -1 if $a < $b, 0 if equal, 1 if $a > $b.
     */
    public function compare(string $a, string $b): int;

    /**
     * Whether $version satisfies $constraint (e.g. "^1.2", ">=1.0 <2.0", "*").
     */
    public function satisfies(string $version, string $constraint): bool;
}
