<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Errors;

/**
 * A GraphQL error entry, shaped for the `errors` array of a response.
 *
 * This is a data object — it is returned in the response, not thrown.
 */
final class GraphQLError
{
    /**
     * @param list<string|int>     $path       Response path to the failing field
     * @param array<string, mixed> $extensions Additional machine-readable detail
     */
    public function __construct(
        public readonly string $message,
        public readonly ErrorCategory $category = ErrorCategory::Internal,
        public readonly array $path = [],
        public readonly array $extensions = [],
    ) {
    }

    /**
     * @return array{message: string, path?: list<string|int>, extensions: array<string, mixed>}
     */
    public function toArray(): array
    {
        $result = ['message' => $this->message];

        if ($this->path !== []) {
            $result['path'] = $this->path;
        }

        $result['extensions'] = array_merge(['category' => $this->category->value], $this->extensions);

        return $result;
    }
}
