<?php

declare(strict_types=1);

namespace OpenMeta\Security\Context;

/**
 * Immutable security context for the current request / actor.
 *
 * @phpstan-type CapabilityList list<string>
 */
final class SecurityContext
{
    /**
     * @param CapabilityList $capabilities
     * @param array<string, mixed> $attributes
     */
    public function __construct(
        public readonly ?string $subjectId = null,
        public readonly array $capabilities = [],
        public readonly array $attributes = [],
    ) {
    }

    public function withCapability(string $capability): self
    {
        $caps = $this->capabilities;

        if (! in_array($capability, $caps, true)) {
            $caps[] = $capability;
        }

        return new self($this->subjectId, $caps, $this->attributes);
    }

    public function hasCapability(string $capability): bool
    {
        return in_array($capability, $this->capabilities, true);
    }
}
