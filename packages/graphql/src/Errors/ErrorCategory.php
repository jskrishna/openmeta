<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Errors;

/**
 * Classification of a GraphQL error, surfaced in the response extensions.
 */
enum ErrorCategory: string
{
    case Validation = 'validation';
    case Authorization = 'authorization';
    case Schema = 'schema';
    case Internal = 'internal';
}
