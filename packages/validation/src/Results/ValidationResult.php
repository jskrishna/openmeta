<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Results;

/**
 * Immutable outcome of a validation run.
 */
final class ValidationResult
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        private readonly bool $passed,
        private readonly array $data,
        private readonly ErrorBag $errors,
    ) {
    }

    public function passed(): bool
    {
        return $this->passed;
    }

    public function failed(): bool
    {
        return ! $this->passed;
    }

    /**
     * @return array<string, mixed>
     */
    public function data(): array
    {
        return $this->data;
    }

    public function errors(): ErrorBag
    {
        return $this->errors;
    }
}
