<?php

declare(strict_types=1);

namespace OpenMeta\Security\Contracts;

/**
 * Framework CSRF token manager contract.
 */
interface CsrfTokenManagerInterface
{
    public function generate(): string;

    public function rotate(): string;

    public function current(): string;

    public function isValid(string $token): bool;

    public function validate(string $token): void;
}
