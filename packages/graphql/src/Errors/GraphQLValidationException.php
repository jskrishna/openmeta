<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Errors;

/**
 * Raised when mutation/query arguments fail validation.
 */
final class GraphQLValidationException extends GraphQLException
{
    /**
     * @param array<string, list<string>> $errors attribute => messages
     */
    public function __construct(string $message, private readonly array $errors = [])
    {
        parent::__construct($message);
    }

    /**
     * @return array<string, list<string>>
     */
    public function errors(): array
    {
        return $this->errors;
    }

    public function category(): ErrorCategory
    {
        return ErrorCategory::Validation;
    }
}
