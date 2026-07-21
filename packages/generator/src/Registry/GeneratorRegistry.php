<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Registry;

use OpenMeta\Generator\Contracts\GeneratorInterface;
use OpenMeta\Generator\Contracts\GeneratorRegistryInterface;
use OpenMeta\Generator\Exceptions\GeneratorNotFoundException;

/**
 * Stores generators by key.
 */
final class GeneratorRegistry implements GeneratorRegistryInterface
{
    /** @var array<string, GeneratorInterface> */
    private array $generators = [];

    public function register(GeneratorInterface $generator): void
    {
        $this->generators[$generator->key()] = $generator;
    }

    public function has(string $key): bool
    {
        return isset($this->generators[$key]);
    }

    public function get(string $key): GeneratorInterface
    {
        return $this->generators[$key] ?? throw GeneratorNotFoundException::forKey($key);
    }

    public function keys(): array
    {
        $keys = array_keys($this->generators);
        sort($keys);

        return $keys;
    }

    public function all(): array
    {
        return array_values($this->generators);
    }
}
