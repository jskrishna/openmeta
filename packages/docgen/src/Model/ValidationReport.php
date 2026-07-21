<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Model;

/**
 * The outcome of a documentation validation run.
 */
final class ValidationReport
{
    /** @var list<ValidationIssue> */
    private array $issues = [];

    public function add(ValidationIssue $issue): void
    {
        $this->issues[] = $issue;
    }

    public function merge(self $other): void
    {
        foreach ($other->issues() as $issue) {
            $this->issues[] = $issue;
        }
    }

    /**
     * @return list<ValidationIssue>
     */
    public function issues(): array
    {
        return $this->issues;
    }

    /**
     * @return list<ValidationIssue>
     */
    public function ofType(IssueType $type): array
    {
        return array_values(array_filter(
            $this->issues,
            static fn (ValidationIssue $issue): bool => $issue->type === $type,
        ));
    }

    public function isClean(): bool
    {
        return $this->issues === [];
    }

    public function count(): int
    {
        return count($this->issues);
    }
}
