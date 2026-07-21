<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Types;

/**
 * The kind of a named GraphQL type.
 */
enum TypeKind: string
{
    case Scalar = 'SCALAR';
    case Object = 'OBJECT';
    case Interface = 'INTERFACE';
    case Union = 'UNION';
    case Enum = 'ENUM';
    case InputObject = 'INPUT_OBJECT';
}
