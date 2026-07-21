<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Exceptions;

/**
 * Missing or invalid credentials (401).
 */
final class AuthenticationException extends RestException
{
    public function __construct(string $message = 'Unauthenticated.', ?\Throwable $previous = null)
    {
        parent::__construct($message, 401, 'unauthenticated', $previous);
    }
}
