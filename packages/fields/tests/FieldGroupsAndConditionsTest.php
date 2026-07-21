<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Tests;

use OpenMeta\Fields\Conditions\Condition;
use OpenMeta\Fields\Conditions\ConditionEvaluator;
use OpenMeta\Fields\Conditions\ConditionGroup;
use OpenMeta\Fields\Conditions\ConditionOperator;
use OpenMeta\Fields\Groups\FieldGroup;
use OpenMeta\Fields\Groups\FieldGroupRegistry;

final class FieldGroupsAndConditionsTest extends FieldsTestCase
{
    public function test_nested_groups_and_ordering(): void
    {
        $child = FieldGroup::fromArray([
            'id' => 'meta',
            'name' => 'meta',
            'order' => 2,
            'fields' => [
                ['name' => 'slug', 'type' => 'text'],
            ],
        ]);

        $parent = FieldGroup::fromArray([
            'id' => 'main',
            'name' => 'main',
            'label' => 'Main',
            'order' => 1,
            'fields' => [
                ['name' => 'title', 'type' => 'text', 'required' => true],
            ],
        ])->withGroup($child);

        $registry = new FieldGroupRegistry();
        $registry->register($parent);
        $registry->register(FieldGroup::fromArray([
            'id' => 'sidebar',
            'name' => 'sidebar',
            'order' => 0,
        ]));

        $all = $registry->all();
        self::assertSame('sidebar', $all[0]->id());
        self::assertSame('main', $all[1]->id());
        self::assertCount(1, $parent->groups());
        self::assertSame('slug', $parent->groups()[0]->fields()[0]->name());
    }

    public function test_condition_operators_and_nesting(): void
    {
        $evaluator = new ConditionEvaluator();
        $values = ['status' => 'publish', 'count' => 5, 'tags' => 'a'];

        self::assertTrue($evaluator->evaluate(
            new Condition('status', ConditionOperator::Equals, 'publish'),
            $values
        ));
        self::assertTrue($evaluator->evaluate(
            new Condition('status', ConditionOperator::NotEquals, 'draft'),
            $values
        ));
        self::assertTrue($evaluator->evaluate(
            new Condition('missing', ConditionOperator::Empty),
            $values
        ));
        self::assertTrue($evaluator->evaluate(
            new Condition('status', ConditionOperator::NotEmpty),
            $values
        ));
        self::assertTrue($evaluator->evaluate(
            new Condition('count', ConditionOperator::GreaterThan, 3),
            $values
        ));
        self::assertTrue($evaluator->evaluate(
            new Condition('count', ConditionOperator::LessThan, 10),
            $values
        ));
        self::assertTrue($evaluator->evaluate(
            new Condition('tags', ConditionOperator::In, ['a', 'b']),
            $values
        ));
        self::assertTrue($evaluator->evaluate(
            new Condition('tags', ConditionOperator::NotIn, ['x']),
            $values
        ));

        $and = ConditionGroup::and([
            new Condition('status', ConditionOperator::Equals, 'publish'),
            new Condition('count', ConditionOperator::GreaterThan, 1),
        ]);
        self::assertTrue($evaluator->evaluate($and, $values));

        $or = ConditionGroup::or([
            new Condition('status', ConditionOperator::Equals, 'draft'),
            new Condition('count', ConditionOperator::Equals, 5),
        ]);
        self::assertTrue($evaluator->evaluate($or, $values));

        $nested = ConditionGroup::fromArray([
            'logic' => 'and',
            'conditions' => [
                ['field' => 'status', 'operator' => 'equals', 'value' => 'publish'],
                [
                    'logic' => 'or',
                    'conditions' => [
                        ['field' => 'count', 'operator' => 'less_than', 'value' => 1],
                        ['field' => 'count', 'operator' => 'greater_than', 'value' => 4],
                    ],
                ],
            ],
        ]);
        self::assertTrue($evaluator->evaluate($nested, $values));
    }
}
