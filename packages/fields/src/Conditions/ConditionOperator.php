<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Conditions;

/**
 * Supported leaf condition operators.
 */
enum ConditionOperator: string
{
    case Equals = 'equals';
    case NotEquals = 'not_equals';
    case Empty = 'empty';
    case NotEmpty = 'not_empty';
    case GreaterThan = 'greater_than';
    case LessThan = 'less_than';
    case In = 'in';
    case NotIn = 'not_in';
}
