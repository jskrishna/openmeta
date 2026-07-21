<?php

declare(strict_types=1);

namespace OpenMeta\Security\Contracts;

/**
 * Low-level capability check (in-memory or host bridge via this interface).
 * Fail closed when undecided.
 */
interface CapabilityCheckerInterface
{
    public function can(string $capability): bool;
}
