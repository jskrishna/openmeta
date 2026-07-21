<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Events;

use OpenMeta\GraphQL\Errors\GraphQLError;

/**
 * Dispatched whenever an error is added to a GraphQL response.
 */
final class ErrorRaised
{
    public function __construct(public readonly GraphQLError $error)
    {
    }
}
