<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Context;

use OpenMeta\Validation\Contracts\RuleInterface;

/**
 * Immutable validation run context (data + rule map + optional custom messages).
 */
final class ValidationContext
{
    /**
     * @param array<string, mixed> $data
     * @param array<string, list<string|RuleInterface>|string|RuleInterface> $rules
     * @param array<string, string> $messages
     */
    public function __construct(
        public readonly array $data,
        public readonly array $rules,
        public readonly array $messages = [],
    ) {
    }
}
