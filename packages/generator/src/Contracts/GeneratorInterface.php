<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Contracts;

use OpenMeta\Generator\Configuration\GeneratorConfiguration;
use OpenMeta\Generator\Files\GeneratedFile;
use OpenMeta\Generator\Manager\GenerationRequest;

/**
 * Produces the files for one kind of artefact (field, repository, command, …).
 *
 * Generators compute file contents only; writing, conflict handling, and events
 * are the manager's responsibility.
 */
interface GeneratorInterface
{
    public function key(): string;

    public function description(): string;

    /**
     * @return list<GeneratedFile>
     */
    public function generate(GenerationRequest $request, GeneratorConfiguration $config): array;
}
