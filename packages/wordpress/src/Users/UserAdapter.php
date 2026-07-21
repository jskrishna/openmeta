<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Users;

use OpenMeta\Security\Contracts\CapabilityCheckerInterface;
use OpenMeta\Wordpress\Runtime\WordPressRuntimeInterface;

/**
 * Current user adapter — fail closed without WordPress.
 */
final class UserAdapter
{
    public function __construct(
        private readonly WordPressRuntimeInterface $runtime,
        private readonly CapabilityCheckerInterface $capabilities,
    ) {
    }

    public function currentUserId(): int
    {
        return $this->runtime->getCurrentUserId();
    }

    public function can(string $capability): bool
    {
        return $this->capabilities->can($capability);
    }
}
