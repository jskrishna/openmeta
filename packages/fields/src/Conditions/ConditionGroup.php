<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Conditions;

use OpenMeta\Fields\Contracts\ConditionInterface;
use OpenMeta\Fields\Exceptions\InvalidFieldException;

/**
 * Composite AND / OR condition group — supports nesting.
 */
final class ConditionGroup implements ConditionInterface
{
    public const AND = 'and';

    public const OR = 'or';

    /**
     * @param list<ConditionInterface> $conditions
     */
    public function __construct(
        private readonly string $logic,
        private readonly array $conditions,
    ) {
        $logic = strtolower($this->logic);

        if ($logic !== self::AND && $logic !== self::OR) {
            throw new InvalidFieldException(sprintf('Unknown condition logic [%s].', $this->logic));
        }
    }

    /**
     * @param list<ConditionInterface> $conditions
     */
    public static function and(array $conditions): self
    {
        return new self(self::AND, $conditions);
    }

    /**
     * @param list<ConditionInterface> $conditions
     */
    public static function or(array $conditions): self
    {
        return new self(self::OR, $conditions);
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): ConditionInterface
    {
        if (isset($data['conditions']) && is_array($data['conditions'])) {
            $logic = isset($data['logic']) && is_string($data['logic']) ? $data['logic'] : self::AND;
            $children = [];

            foreach ($data['conditions'] as $child) {
                if (! is_array($child)) {
                    continue;
                }

                $children[] = self::fromArray($child);
            }

            return new self($logic, $children);
        }

        return Condition::fromArray($data);
    }

    public function matches(array $values): bool
    {
        if ($this->conditions === []) {
            return true;
        }

        if (strtolower($this->logic) === self::OR) {
            foreach ($this->conditions as $condition) {
                if ($condition->matches($values)) {
                    return true;
                }
            }

            return false;
        }

        foreach ($this->conditions as $condition) {
            if (! $condition->matches($values)) {
                return false;
            }
        }

        return true;
    }

    public function toArray(): array
    {
        return [
            'logic' => strtolower($this->logic),
            'conditions' => array_map(
                static fn (ConditionInterface $c): array => $c->toArray(),
                $this->conditions
            ),
        ];
    }
}
