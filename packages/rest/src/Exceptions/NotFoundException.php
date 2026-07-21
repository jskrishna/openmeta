<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Exceptions;

/**
 * No matching route or resource (404).
 */
final class NotFoundException extends RestException
{
    public function __construct(string $message = 'Resource not found.', ?\Throwable $previous = null)
    {
        parent::__construct($message, 404, 'not_found', $previous);
    }
}
