<?php

declare(strict_types=1);

namespace OpenMeta\Api\Exceptions;

final class AuthenticationException extends ApiException
{
    public function __construct(string $message = 'Unauthenticated.')
    {
        parent::__construct($message, 401, 'unauthenticated');
    }
}
