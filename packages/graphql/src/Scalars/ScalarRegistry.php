<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Scalars;

use OpenMeta\GraphQL\Errors\DuplicateTypeException;
use OpenMeta\GraphQL\Errors\TypeNotFoundException;

/**
 * Registry of scalar types. The five built-in scalars are seeded by
 * {@see registerDefaults()}.
 */
final class ScalarRegistry
{
    public const BUILT_IN = ['String', 'Int', 'Float', 'Boolean', 'ID'];

    /** @var array<string, ScalarType> */
    private array $scalars = [];

    public function registerDefaults(): void
    {
        foreach (self::BUILT_IN as $name) {
            $this->scalars[$name] ??= new ScalarType($name);
        }
    }

    public function register(ScalarType $scalar): void
    {
        if (isset($this->scalars[$scalar->name])) {
            throw DuplicateTypeException::named('Scalar', $scalar->name);
        }

        $this->scalars[$scalar->name] = $scalar;
    }

    public function has(string $name): bool
    {
        return isset($this->scalars[$name]);
    }

    public function get(string $name): ScalarType
    {
        return $this->scalars[$name] ?? throw TypeNotFoundException::named($name);
    }

    /**
     * @return list<ScalarType>
     */
    public function all(): array
    {
        return array_values($this->scalars);
    }

    public function isBuiltIn(string $name): bool
    {
        return in_array($name, self::BUILT_IN, true);
    }
}
