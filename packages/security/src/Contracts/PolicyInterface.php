<?php

declare(strict_types=1);

namespace OpenMeta\Security\Contracts;

/**
 * Authorization policy for a subject / resource pair.
 */
interface PolicyInterface
{
    /**
     * @param array<string, mixed> $context
     */
    public function allows(string $ability, mixed $subject, mixed $resource = null, array $context = []): bool;
}
