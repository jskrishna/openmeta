<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Scalars;

use OpenMeta\GraphQL\Types\TypeKind;

/**
 * A GraphQL scalar type. An optional coercer serializes/parses values; when
 * absent the value passes through unchanged.
 */
final class ScalarType
{
    /** @var (callable(mixed): mixed)|null */
    private $serializer;

    /**
     * @param (callable(mixed): mixed)|null $serializer
     */
    public function __construct(
        public readonly string $name,
        public readonly string $description = '',
        ?callable $serializer = null,
    ) {
        $this->serializer = $serializer;
    }

    public function kind(): TypeKind
    {
        return TypeKind::Scalar;
    }

    public function serialize(mixed $value): mixed
    {
        if ($this->serializer === null) {
            return $value;
        }

        return ($this->serializer)($value);
    }
}
