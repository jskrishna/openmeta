<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Manager;

use OpenMeta\Generator\Files\FileStatus;

/**
 * The outcome of a generation run: one entry per file.
 */
final class GenerationResult
{
    /**
     * @param list<FileOutcome> $files
     */
    public function __construct(
        public readonly string $key,
        public readonly array $files,
        public readonly bool $dryRun = false,
    ) {
    }

    /**
     * @return list<FileOutcome>
     */
    public function written(): array
    {
        return array_values(array_filter($this->files, static fn (FileOutcome $file): bool => $file->wasWritten()));
    }

    /**
     * @return list<FileOutcome>
     */
    public function skipped(): array
    {
        return array_values(array_filter(
            $this->files,
            static fn (FileOutcome $file): bool => $file->status === FileStatus::Skipped,
        ));
    }

    public function hasSkips(): bool
    {
        return $this->skipped() !== [];
    }
}
