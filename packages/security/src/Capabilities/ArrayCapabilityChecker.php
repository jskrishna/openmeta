<?php

declare(strict_types=1);

namespace OpenMeta\Security\Capabilities;

use OpenMeta\Security\Contracts\CapabilityCheckerInterface;

/**
 * In-memory capability checker. Fail closed unless a capability is explicitly granted.
 * Used for tests and non-WordPress runtimes.
 */
final class ArrayCapabilityChecker implements CapabilityCheckerInterface
{
    /** @param list<string> $granted */
    public function __construct(private array $granted = [])
    {
    }

    /**
     * @param list<string> $capabilities
     */
    public function grant(array $capabilities): void
    {
        $this->granted = array_values(array_unique([...$this->granted, ...$capabilities]));
    }

    public function revoke(string $capability): void
    {
        $this->granted = array_values(array_filter(
            $this->granted,
            static fn (string $cap): bool => $cap !== $capability
        ));
    }

    public function can(string $capability): bool
    {
        return in_array($capability, $this->granted, true);
    }
}
