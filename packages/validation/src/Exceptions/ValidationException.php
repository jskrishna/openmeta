<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Exceptions;

use OpenMeta\Core\Exceptions\OpenMetaException;
use OpenMeta\Validation\Results\ErrorBag;

/**
 * Thrown when {@see \OpenMeta\Validation\Validator\Validator::validate()} fails.
 */
final class ValidationException extends OpenMetaException
{
    public function __construct(
        private readonly ErrorBag $errors,
        string $message = 'The given data was invalid.',
    ) {
        parent::__construct($message);
    }

    public function errors(): ErrorBag
    {
        return $this->errors;
    }
}
