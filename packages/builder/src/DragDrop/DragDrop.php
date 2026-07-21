<?php

declare(strict_types=1);

namespace OpenMeta\Builder\DragDrop;

use OpenMeta\Builder\Canvas\Canvas;
use OpenMeta\Builder\Canvas\CanvasNode;
use OpenMeta\Builder\Exceptions\BuilderException;
use OpenMeta\Builder\Support\IdGenerator;
use OpenMeta\Fields\Registry\FieldRegistry;

/**
 * Drag & drop infrastructure — reordering, nested drops, validation.
 */
final class DragDrop
{
    public function __construct(
        private readonly FieldRegistry $fields,
        private readonly DropValidator $validator,
    ) {
    }

    public function dropNew(
        Canvas $canvas,
        string $type,
        string $name,
        ?int $index = null,
        ?DropTarget $target = null,
    ): CanvasNode {
        $source = new DragSource($type, 'field-type');
        $target ??= new DropTarget('canvas-root', ['field-type', 'component'], $index);
        $this->validator->validate($source, $target);

        if (! $this->fields->has($type)) {
            throw new BuilderException(sprintf('Cannot drop unknown field type [%s].', $type));
        }

        $node = new CanvasNode(IdGenerator::node('field'), $type, $name, ['label' => $name]);
        $canvas->add($node, $target->index ?? $index);
        $canvas->select($node->id);

        return $node;
    }

    public function move(Canvas $canvas, string $id, int $toIndex, ?DropTarget $target = null): void
    {
        $source = new DragSource($id, 'node', $id);
        $target ??= new DropTarget('canvas-root', ['node'], $toIndex);
        $this->validator->validate($source, $target);

        $canvas->move($id, $toIndex);
        $canvas->select($id);
    }

    public function moveSelected(Canvas $canvas, string $direction): void
    {
        $selected = $canvas->selected();
        if ($selected === null) {
            throw new BuilderException('No node selected.');
        }

        $index = null;
        foreach ($canvas->nodes() as $i => $node) {
            if ($node->id === $selected->id) {
                $index = $i;
                break;
            }
        }

        if ($index === null) {
            return;
        }

        $target = $direction === 'up' ? $index - 1 : $index + 1;
        if ($target < 0 || $target >= $canvas->count()) {
            return;
        }

        $this->move($canvas, $selected->id, $target);
    }
}
