<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Compatibility;

/**
 * The result of a compatibility check: a verdict plus human-readable issues.
 */
final class CompatibilityReport
{
    /**
     * @param list<string> $issues
     */
    public function __construct(
        public readonly bool $compatible,
        public readonly array $issues = [],
    ) {
    }

    /**
     * @param list<string> $issues
     */
    public static function fromIssues(array $issues): self
    {
        return new self($issues === [], $issues);
    }
}
