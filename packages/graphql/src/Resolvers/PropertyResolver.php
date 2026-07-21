<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Resolvers;

use OpenMeta\GraphQL\Contracts\ResolverInterface;

/**
 * Default field resolution: read the field name as an array key or object
 * property/getter from the parent value.
 */
final class PropertyResolver implements ResolverInterface
{
    public function __construct(private readonly string $field)
    {
    }

    public function resolve(mixed $root, array $args, ResolutionContext $context): mixed
    {
        if (is_array($root)) {
            return $root[$this->field] ?? null;
        }

        if (is_object($root)) {
            $getter = 'get' . ucfirst($this->field);

            if (method_exists($root, $getter)) {
                return $root->{$getter}();
            }

            if (method_exists($root, $this->field)) {
                return $root->{$this->field}();
            }

            if (property_exists($root, $this->field)) {
                return $root->{$this->field};
            }
        }

        return null;
    }
}
