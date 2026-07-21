<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Exceptions;

use OpenMeta\Core\Exceptions\OpenMetaException;

/**
 * Base REST exception with HTTP status and machine-readable code.
 */
class RestException extends OpenMetaException
{
    public function __construct(
        string $message = '',
        private readonly int $status = 500,
        private readonly string $errorCode = 'server_error',
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $status, $previous);
    }

    public function status(): int
    {
        return $this->status;
    }

    public function errorCode(): string
    {
        return $this->errorCode;
    }
}
