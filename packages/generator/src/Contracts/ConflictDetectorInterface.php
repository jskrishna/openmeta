<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Contracts;

use OpenMeta\Generator\Configuration\GeneratorConfiguration;
use OpenMeta\Generator\Files\Conflict;

/**
 * Detects existing-file, naming-collision, and reserved-namespace conflicts.
 */
interface ConflictDetectorInterface
{
    public function detect(string $path, string $fqcn, GeneratorConfiguration $config): ?Conflict;
}
