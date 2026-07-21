<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Contracts;

use OpenMeta\Generator\Exceptions\GeneratorNotFoundException;

/**
 * Stores generators keyed by their name (e.g. "field", "repository").
 */
interface GeneratorRegistryInterface
{
    public function register(GeneratorInterface $generator): void;

    public function has(string $key): bool;

    /**
     * @throws GeneratorNotFoundException
     */
    public function get(string $key): GeneratorInterface;

    /**
     * @return list<string>
     */
    public function keys(): array;

    /**
     * @return list<GeneratorInterface>
     */
    public function all(): array;
}
