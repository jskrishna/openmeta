<?php

declare(strict_types=1);

namespace OpenMeta\Security\Contracts;

/**
 * Permission gate — can the current identity perform an OpenMeta permission?
 */
interface GateInterface
{
    public function can(string $permission): bool;

    public function cannot(string $permission): bool;

    public function authorize(string $permission): void;

    public function flushCache(): void;
}
