<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

use Closure;
use OpenMeta\Validation\Contracts\RuleInterface;

/**
 * Wraps a Closure as a custom rule. Closure signature:
 * `function (string $attribute, mixed $value, array $parameters, array $data): bool`
 */
final class ClosureRule implements RuleInterface
{
    /**
     * @param Closure(string, mixed, list<string>, array<string, mixed>): bool $callback
     */
    public function __construct(
        private readonly string $name,
        private readonly Closure $callback,
        private readonly string $message = 'The :attribute field is invalid.',
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function passes(string $attribute, mixed $value, array $parameters = [], array $data = []): bool
    {
        return (bool) ($this->callback)($attribute, $value, $parameters, $data);
    }

    public function message(): string
    {
        return $this->message;
    }
}
