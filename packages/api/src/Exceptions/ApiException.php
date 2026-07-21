<?php

declare(strict_types=1);

namespace OpenMeta\Api\Exceptions;

use OpenMeta\Core\Exceptions\OpenMetaException;

class ApiException extends OpenMetaException
{
    public function __construct(
        string $message,
        private readonly int $status = 400,
        private readonly string $codeKey = 'api_error',
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $status, $previous);
    }

    public function status(): int
    {
        return $this->status;
    }

    public function codeKey(): string
    {
        return $this->codeKey;
    }
}
