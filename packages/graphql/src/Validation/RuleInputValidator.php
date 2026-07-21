<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Validation;

use OpenMeta\GraphQL\Contracts\InputValidatorInterface;
use OpenMeta\Validation\Validation;

/**
 * Bridges GraphQL argument validation to the OpenMeta Validation package.
 *
 * It never re-implements validation — it delegates to {@see Validation}.
 */
final class RuleInputValidator implements InputValidatorInterface
{
    public function validate(array $args, array $rules): ValidationOutcome
    {
        if ($rules === []) {
            return ValidationOutcome::pass();
        }

        $result = Validation::make($args, $rules)->result();

        if ($result->passed()) {
            return ValidationOutcome::pass();
        }

        return ValidationOutcome::fail($result->errors()->all());
    }
}
