<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Errors;

use OpenMeta\GraphQL\Contracts\ErrorHandlerInterface;
use Throwable;

/**
 * Converts thrown exceptions into consistent {@see GraphQLError} entries.
 *
 * Known GraphQL exceptions keep their category and message; anything else is
 * reported as a generic internal error so implementation details never leak.
 */
final class ErrorHandler implements ErrorHandlerInterface
{
    public function handle(Throwable $exception, array $path = []): GraphQLError
    {
        if ($exception instanceof GraphQLValidationException) {
            return new GraphQLError(
                $exception->getMessage(),
                ErrorCategory::Validation,
                $path,
                ['validation' => $exception->errors()],
            );
        }

        if ($exception instanceof GraphQLException) {
            return new GraphQLError($exception->getMessage(), $exception->category(), $path);
        }

        return new GraphQLError('Internal server error.', ErrorCategory::Internal, $path);
    }
}
