<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Contracts;

use OpenMeta\GraphQL\Errors\GraphQLError;
use Throwable;

/**
 * Converts a thrown exception into a consistent GraphQL error entry.
 */
interface ErrorHandlerInterface
{
    /**
     * @param list<string|int> $path
     */
    public function handle(Throwable $exception, array $path = []): GraphQLError;
}
