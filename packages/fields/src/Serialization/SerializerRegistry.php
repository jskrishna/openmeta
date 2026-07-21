<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Serialization;

use OpenMeta\Fields\Contracts\FieldSerializerInterface;
use OpenMeta\Fields\Exceptions\InvalidFieldException;

/**
 * Pluggable serializer catalog (array / json / object / custom).
 */
final class SerializerRegistry
{
    /** @var array<string, FieldSerializerInterface> */
    private array $serializers = [];

    public function register(string $name, FieldSerializerInterface $serializer): void
    {
        $this->serializers[$name] = $serializer;
    }

    public function has(string $name): bool
    {
        return isset($this->serializers[$name]);
    }

    public function get(string $name): FieldSerializerInterface
    {
        if (! isset($this->serializers[$name])) {
            throw new InvalidFieldException(sprintf('Unknown serializer [%s].', $name));
        }

        return $this->serializers[$name];
    }
}
