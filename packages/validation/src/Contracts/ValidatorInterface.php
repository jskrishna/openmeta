<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Contracts;

use OpenMeta\Validation\Results\ErrorBag;
use OpenMeta\Validation\Results\ValidationResult;

/**
 * Runs a rules map against a payload.
 */
interface ValidatorInterface
{
    public function passes(): bool;

    public function fails(): bool;

    public function errors(): ErrorBag;

    public function result(): ValidationResult;

    /**
     * @return array<string, mixed>
     *
     * @throws \OpenMeta\Validation\Exceptions\ValidationException
     */
    public function validate(): array;
}
