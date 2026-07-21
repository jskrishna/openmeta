<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Contracts;

use OpenMeta\Generator\Files\GeneratedFile;

/**
 * Post-processes a generated file before it is written — the extension point
 * for formatters, header injectors, and the like.
 */
interface FileProcessorInterface
{
    public function process(GeneratedFile $file): GeneratedFile;
}
