<?php

declare(strict_types=1);

namespace OpenMeta\Security\Contracts;

/**
 * Authorization façade (permissions + policies).
 */
interface AuthorizerInterface
{
    public function can(string $permission): bool;

    public function denyUnless(string $permission): void;

    public function registerPolicy(string $name, PolicyInterface $policy): void;

    /**
     * @param array<string, mixed> $context
     */
    public function allows(
        string $policy,
        string $ability,
        mixed $subject,
        mixed $resource = null,
        array $context = []
    ): bool;
}
