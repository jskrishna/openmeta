<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Files;

/**
 * A file a generator wants to produce: its target path, rendered contents,
 * and intended action.
 */
final class GeneratedFile
{
    public function __construct(
        public readonly string $path,
        public readonly string $contents,
        public readonly FileAction $action = FileAction::Create,
        public readonly string $fqcn = '',
    ) {
    }

    public function withContents(string $contents): self
    {
        return new self($this->path, $contents, $this->action, $this->fqcn);
    }
}
