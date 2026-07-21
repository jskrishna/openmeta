<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Exceptions;

/**
 * Authenticated but not permitted (403).
 */
final class AuthorizationException extends RestException
{
    public function __construct(string $message = 'Forbidden.', ?\Throwable $previous = null)
    {
        parent::__construct($message, 403, 'forbidden', $previous);
    }
}
