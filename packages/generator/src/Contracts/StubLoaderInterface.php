<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Contracts;

use OpenMeta\Generator\Exceptions\StubNotFoundException;

/**
 * Loads named stub templates from registered search paths.
 */
interface StubLoaderInterface
{
    /**
     * @throws StubNotFoundException
     */
    public function load(string $name): string;

    public function has(string $name): bool;

    /**
     * Register an additional stub directory (extension point).
     */
    public function addPath(string $path): void;
}
