<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Conditions;

use OpenMeta\Fields\Contracts\ConditionInterface;

/**
 * Evaluates condition trees against a values bag. Extensible via ConditionInterface.
 */
final class ConditionEvaluator
{
    /**
     * @param array<string, mixed> $values
     */
    public function evaluate(ConditionInterface|array|null $condition, array $values): bool
    {
        if ($condition === null || $condition === []) {
            return true;
        }

        if (is_array($condition)) {
            $condition = ConditionGroup::fromArray($condition);
        }

        return $condition->matches($values);
    }
}
