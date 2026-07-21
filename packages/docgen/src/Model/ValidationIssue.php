<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Model;

/**
 * A single documentation validation finding.
 */
final class ValidationIssue
{
    public function __construct(
        public readonly IssueType $type,
        public readonly string $file,
        public readonly string $message,
    ) {
    }

    public function __toString(): string
    {
        return sprintf('[%s] %s — %s', $this->type->value, $this->file, $this->message);
    }
}
