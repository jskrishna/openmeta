<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Exceptions;

use OpenMeta\Validation\Results\ErrorBag;

final class ValidationFailedException extends FieldException
{
    public function __construct(
        string $message,
        private readonly ?ErrorBag $errors = null,
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function errors(): ErrorBag
    {
        return $this->errors ?? ErrorBag::empty();
    }
}
