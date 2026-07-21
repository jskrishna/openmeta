<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Errors;

/**
 * Raised when the schema is malformed or a referenced type is missing.
 */
class SchemaException extends GraphQLException
{
    public function category(): ErrorCategory
    {
        return ErrorCategory::Schema;
    }
}
