<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Manager;

use OpenMeta\GraphQL\Errors\GraphQLError;

/**
 * The outcome of executing an operation: data and/or errors, shaped for a
 * GraphQL response body.
 */
final class ExecutionResult
{
    /**
     * @param list<GraphQLError>   $errors
     * @param array<string, mixed> $extensions
     */
    public function __construct(
        public readonly mixed $data,
        public readonly array $errors = [],
        public readonly array $extensions = [],
    ) {
    }

    public function hasErrors(): bool
    {
        return $this->errors !== [];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $result = ['data' => $this->data];

        if ($this->errors !== []) {
            $result['errors'] = array_map(
                static fn (GraphQLError $error): array => $error->toArray(),
                $this->errors,
            );
        }

        if ($this->extensions !== []) {
            $result['extensions'] = $this->extensions;
        }

        return $result;
    }
}
