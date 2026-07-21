<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Exceptions;

use OpenMeta\Validation\Results\ErrorBag;

/**
 * Request validation failed (422).
 */
final class ValidationException extends RestException
{
    public function __construct(
        string $message = 'Validation failed.',
        private readonly ?ErrorBag $errors = null,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, 422, 'validation_error', $previous);
    }

    public function errors(): ?ErrorBag
    {
        return $this->errors;
    }
}
