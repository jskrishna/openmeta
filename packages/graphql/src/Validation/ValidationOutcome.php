<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Validation;

/**
 * Result of validating operation arguments.
 */
final class ValidationOutcome
{
    /**
     * @param array<string, list<string>> $errors attribute => messages
     */
    public function __construct(
        public readonly bool $passed,
        public readonly array $errors = [],
    ) {
    }

    public static function pass(): self
    {
        return new self(true);
    }

    /**
     * @param array<string, list<string>> $errors
     */
    public static function fail(array $errors): self
    {
        return new self(false, $errors);
    }

    public function failed(): bool
    {
        return ! $this->passed;
    }
}
