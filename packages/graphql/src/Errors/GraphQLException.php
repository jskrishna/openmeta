<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Errors;

use OpenMeta\Core\Exceptions\OpenMetaException;

/**
 * Base exception for the GraphQL package.
 */
class GraphQLException extends OpenMetaException
{
    public function category(): ErrorCategory
    {
        return ErrorCategory::Internal;
    }
}
