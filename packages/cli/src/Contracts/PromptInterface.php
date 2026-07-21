<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Contracts;

/**
 * Interactive prompts. Reads are stream-backed so they can be scripted in tests.
 */
interface PromptInterface
{
    public function ask(string $question, ?string $default = null): string;

    public function confirm(string $question, bool $default = false): bool;

    /**
     * @param list<string> $choices
     */
    public function choice(string $question, array $choices, ?string $default = null): string;

    public function secret(string $question): string;
}
