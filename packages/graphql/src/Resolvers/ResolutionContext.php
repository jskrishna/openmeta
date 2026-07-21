<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Resolvers;

/**
 * Per-request context handed to every resolver.
 *
 * Carries the viewer identity and arbitrary request attributes; it never
 * carries storage handles (resolvers receive services via the container).
 */
final class ResolutionContext
{
    /**
     * @param array<string, mixed> $attributes
     */
    public function __construct(
        public readonly mixed $viewer = null,
        private readonly array $attributes = [],
    ) {
    }

    public function attribute(string $key, mixed $default = null): mixed
    {
        return $this->attributes[$key] ?? $default;
    }

    public function withAttribute(string $key, mixed $value): self
    {
        $attributes = $this->attributes;
        $attributes[$key] = $value;

        return new self($this->viewer, $attributes);
    }

    /**
     * @return array<string, mixed>
     */
    public function attributes(): array
    {
        return $this->attributes;
    }
}
