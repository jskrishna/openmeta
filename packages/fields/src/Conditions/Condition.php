<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Conditions;

use OpenMeta\Fields\Contracts\ConditionInterface;
use OpenMeta\Support\Arr\Arr;

/**
 * Leaf condition against a single field value.
 */
final class Condition implements ConditionInterface
{
    public function __construct(
        private readonly string $field,
        private readonly ConditionOperator $operator,
        private readonly mixed $expected = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $field = isset($data['field']) && is_string($data['field']) ? $data['field'] : '';
        $op = isset($data['operator']) && is_string($data['operator'])
            ? ConditionOperator::from($data['operator'])
            : ConditionOperator::Equals;

        return new self($field, $op, $data['value'] ?? $data['expected'] ?? null);
    }

    public function matches(array $values): bool
    {
        $actual = Arr::get($values, $this->field);

        return match ($this->operator) {
            ConditionOperator::Equals => $this->looseEquals($actual, $this->expected),
            ConditionOperator::NotEquals => ! $this->looseEquals($actual, $this->expected),
            ConditionOperator::Empty => $this->isEmpty($actual),
            ConditionOperator::NotEmpty => ! $this->isEmpty($actual),
            ConditionOperator::GreaterThan => is_numeric($actual) && is_numeric($this->expected)
                && (float) $actual > (float) $this->expected,
            ConditionOperator::LessThan => is_numeric($actual) && is_numeric($this->expected)
                && (float) $actual < (float) $this->expected,
            ConditionOperator::In => is_array($this->expected) && in_array($actual, $this->expected, true),
            ConditionOperator::NotIn => is_array($this->expected) && ! in_array($actual, $this->expected, true),
        };
    }

    public function toArray(): array
    {
        return [
            'field' => $this->field,
            'operator' => $this->operator->value,
            'value' => $this->expected,
        ];
    }

    private function isEmpty(mixed $value): bool
    {
        return $value === null || $value === '' || $value === [] || $value === false;
    }

    private function looseEquals(mixed $actual, mixed $expected): bool
    {
        if (is_scalar($actual) && is_scalar($expected)) {
            return (string) $actual === (string) $expected;
        }

        return $actual === $expected;
    }
}
