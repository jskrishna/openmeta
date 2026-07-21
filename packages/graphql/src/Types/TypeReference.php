<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Types;

/**
 * An immutable reference to a named type, with optional list / non-null wrappers.
 *
 * Examples:
 *   TypeReference::named('String')                    → String
 *   TypeReference::named('String')->nonNull()         → String!
 *   TypeReference::named('Post')->listOf(true)        → [Post!]
 *   TypeReference::named('Post')->listOf(true)->nonNull() → [Post!]!
 */
final class TypeReference
{
    private function __construct(
        public readonly string $name,
        public readonly bool $nonNull = false,
        public readonly bool $list = false,
        public readonly bool $listItemNonNull = false,
    ) {
    }

    public static function named(string $name): self
    {
        return new self($name);
    }

    public function nonNull(): self
    {
        return new self($this->name, true, $this->list, $this->listItemNonNull);
    }

    public function listOf(bool $itemNonNull = false): self
    {
        return new self($this->name, false, true, $itemNonNull);
    }

    /**
     * The underlying named type, stripped of list / non-null wrappers.
     */
    public function baseName(): string
    {
        return $this->name;
    }

    public function toSdl(): string
    {
        if ($this->list) {
            $inner = $this->name . ($this->listItemNonNull ? '!' : '');

            return '[' . $inner . ']' . ($this->nonNull ? '!' : '');
        }

        return $this->name . ($this->nonNull ? '!' : '');
    }
}
