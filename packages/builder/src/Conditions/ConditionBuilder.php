<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Conditions;

use OpenMeta\Builder\Canvas\CanvasNode;
use OpenMeta\Fields\Conditions\ConditionEvaluator as FieldsConditionEvaluator;
use OpenMeta\Fields\Conditions\ConditionGroup;
use OpenMeta\Fields\Conditions\ConditionOperator;
use OpenMeta\Fields\Conditions\Condition as FieldCondition;

/**
 * Condition builder — reuses the Field Engine condition system.
 */
final class ConditionBuilder
{
    public function __construct(private readonly FieldsConditionEvaluator $evaluator)
    {
    }

    /**
     * @param array<string, mixed>|null $condition
     * @param array<string, mixed> $values
     */
    public function evaluate(?array $condition, array $values): bool
    {
        return $this->evaluator->evaluate($condition, $values);
    }

    /**
     * @param list<CanvasNode> $nodes
     * @param array<string, mixed> $values
     * @return list<CanvasNode>
     */
    public function visibleNodes(array $nodes, array $values): array
    {
        return array_values(array_filter(
            $nodes,
            fn (CanvasNode $node): bool => $this->evaluate($node->condition, $values)
        ));
    }

    public function equals(string $field, mixed $expected): array
    {
        return (new FieldCondition($field, ConditionOperator::Equals, $expected))->toArray();
    }

    public function notEmpty(string $field): array
    {
        return (new FieldCondition($field, ConditionOperator::NotEmpty))->toArray();
    }

    /**
     * @param list<array<string, mixed>> $conditions
     * @return array<string, mixed>
     */
    public function and(array $conditions): array
    {
        $children = [];
        foreach ($conditions as $condition) {
            $children[] = ConditionGroup::fromArray($condition);
        }

        return ConditionGroup::and($children)->toArray();
    }
}
