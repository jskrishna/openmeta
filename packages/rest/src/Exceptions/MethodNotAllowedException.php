<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Exceptions;

/**
 * Route exists but HTTP method is not allowed (405).
 */
final class MethodNotAllowedException extends RestException
{
    /**
     * @param list<string> $allowedMethods
     */
    public function __construct(
        string $message = 'Method not allowed.',
        private readonly array $allowedMethods = [],
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, 405, 'method_not_allowed', $previous);
    }

    /** @return list<string> */
    public function allowedMethods(): array
    {
        return $this->allowedMethods;
    }
}
