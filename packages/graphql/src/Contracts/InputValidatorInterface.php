<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Contracts;

use OpenMeta\GraphQL\Validation\ValidationOutcome;

/**
 * Validates operation arguments.
 *
 * Reuses the Validation package — it never re-implements validation.
 */
interface InputValidatorInterface
{
    /**
     * @param array<string, mixed>        $args
     * @param array<string, list<string>> $rules attribute => rules
     */
    public function validate(array $args, array $rules): ValidationOutcome;
}
