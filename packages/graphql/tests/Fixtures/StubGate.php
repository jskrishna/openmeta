<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Tests\Fixtures;

use OpenMeta\Security\Contracts\GateInterface;
use OpenMeta\Security\Exceptions\AuthorizationException;

/**
 * Test double for the Security gate: permissions are granted explicitly.
 */
final class StubGate implements GateInterface
{
    /** @var array<string, true> */
    private array $granted = [];

    public function grant(string ...$permissions): void
    {
        foreach ($permissions as $permission) {
            $this->granted[$permission] = true;
        }
    }

    public function can(string $permission): bool
    {
        return isset($this->granted[$permission]);
    }

    public function cannot(string $permission): bool
    {
        return ! $this->can($permission);
    }

    public function authorize(string $permission): void
    {
        if ($this->cannot($permission)) {
            throw new AuthorizationException(sprintf('Unauthorized for [%s].', $permission));
        }
    }

    public function flushCache(): void
    {
        // No cache in the stub.
    }
}
