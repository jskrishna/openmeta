<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Manager;

/**
 * Options controlling a generation run.
 *
 * `dryRun`/`preview` never touch disk; `force` allows overwriting an existing
 * file (explicit confirmation) — without it, existing files are skipped.
 */
final class GenerationOptions
{
    public function __construct(
        public readonly bool $dryRun = false,
        public readonly bool $force = false,
        public readonly bool $preview = false,
    ) {
    }

    public function writesToDisk(): bool
    {
        return ! $this->dryRun && ! $this->preview;
    }
}
