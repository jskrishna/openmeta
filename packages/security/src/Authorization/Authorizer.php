<?php

declare(strict_types=1);

namespace OpenMeta\Security\Authorization;

use OpenMeta\Security\Contracts\AuthorizerInterface;
use OpenMeta\Security\Contracts\GateInterface;
use OpenMeta\Security\Contracts\PolicyInterface;
use OpenMeta\Security\Exceptions\AuthorizationException;
use OpenMeta\Security\Exceptions\PermissionDeniedException;

/**
 * Authorization façade: Gate permissions + optional policies.
 */
final class Authorizer implements AuthorizerInterface
{
    /** @var array<string, PolicyInterface> */
    private array $policies = [];

    public function __construct(private readonly GateInterface $gate)
    {
    }

    public function registerPolicy(string $name, PolicyInterface $policy): void
    {
        $this->policies[$name] = $policy;
    }

    public function can(string $permission): bool
    {
        return $this->gate->can($permission);
    }

    /**
     * @throws PermissionDeniedException
     */
    public function denyUnless(string $permission): void
    {
        if ($this->gate->cannot($permission)) {
            throw new PermissionDeniedException(
                sprintf('Permission denied [%s].', $permission)
            );
        }
    }

    /**
     * @param array<string, mixed> $context
     */
    public function allows(
        string $policy,
        string $ability,
        mixed $subject,
        mixed $resource = null,
        array $context = []
    ): bool {
        if (! isset($this->policies[$policy])) {
            return false;
        }

        return $this->policies[$policy]->allows($ability, $subject, $resource, $context);
    }

    /**
     * @param array<string, mixed> $context
     *
     * @throws AuthorizationException
     */
    public function authorizePolicy(
        string $policy,
        string $ability,
        mixed $subject,
        mixed $resource = null,
        array $context = []
    ): void {
        if (! $this->allows($policy, $ability, $subject, $resource, $context)) {
            throw new AuthorizationException(
                sprintf('Policy [%s] denied ability [%s].', $policy, $ability)
            );
        }
    }
}
