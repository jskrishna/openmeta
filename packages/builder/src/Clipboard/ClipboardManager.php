<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Clipboard;

use OpenMeta\Builder\Canvas\CanvasNode;
use OpenMeta\Builder\Exceptions\BuilderException;
use OpenMeta\Builder\Support\IdGenerator;

/**
 * Copy / cut / paste infrastructure for canvas nodes.
 */
final class ClipboardManager
{
    /** @var list<array<string, mixed>> */
    private array $buffer = [];

    private string $mode = 'copy';

    /** @param list<CanvasNode> $nodes */
    public function copy(array $nodes): void
    {
        $this->buffer = array_map(static fn (CanvasNode $node): array => $node->toArray(), $nodes);
        $this->mode = 'copy';
    }

    /** @param list<CanvasNode> $nodes */
    public function cut(array $nodes): void
    {
        $this->copy($nodes);
        $this->mode = 'cut';
    }

    public function hasContent(): bool
    {
        return $this->buffer !== [];
    }

    public function mode(): string
    {
        return $this->mode;
    }

    /** @return list<CanvasNode> */
    public function paste(): array
    {
        if ($this->buffer === []) {
            throw new BuilderException('Clipboard is empty.');
        }

        $nodes = [];
        foreach ($this->buffer as $row) {
            $row['id'] = IdGenerator::node('field');
            $nodes[] = CanvasNode::fromArray($row);
        }

        if ($this->mode === 'cut') {
            $this->buffer = [];
        }

        return $nodes;
    }

    public function clear(): void
    {
        $this->buffer = [];
        $this->mode = 'copy';
    }
}
