<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Contracts;

use OpenMeta\Generator\Manager\GenerationRequest;
use OpenMeta\Generator\Manager\GenerationResult;

/**
 * Public façade: run a generator, handling conflicts, dry-run/preview, file
 * processors, and lifecycle events.
 */
interface GeneratorManagerInterface
{
    public function run(GenerationRequest $request): GenerationResult;

    public function generators(): GeneratorRegistryInterface;

    public function addProcessor(FileProcessorInterface $processor): void;
}
