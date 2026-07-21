<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Contracts;

/**
 * Side-effect free validation rule.
 */
interface RuleInterface
{
    /**
     * Stable rule id (e.g. "required", "min").
     */
    public function name(): string;

    /**
     * @param list<string> $parameters
     * @param array<string, mixed> $data Full payload under validation
     */
    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool;

    /**
     * Default message template. Placeholders: :attribute, :value, and rule params (:min, :max, …).
     */
    public function message(): string;
}
