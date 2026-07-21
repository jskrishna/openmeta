<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Rules;

use OpenMeta\Validation\Contracts\RuleInterface;

/**
 * Abstract built-in rule with a fixed name + message template.
 */
abstract class Rule implements RuleInterface
{
    public function __construct(
        private readonly string $name,
        private readonly string $message,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function message(): string
    {
        return $this->message;
    }
}
