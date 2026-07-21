<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Errors;

/**
 * Raised when a resolver or field is not permitted for the current identity.
 */
final class GraphQLAuthorizationException extends GraphQLException
{
    public static function forPermission(string $permission): self
    {
        return new self(sprintf('Not authorized for [%s].', $permission));
    }

    public function category(): ErrorCategory
    {
        return ErrorCategory::Authorization;
    }
}
