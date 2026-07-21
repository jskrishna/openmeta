<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Properties;

use OpenMeta\Builder\Canvas\CanvasNode;
use OpenMeta\Fields\Definitions\FieldDefinition;

/**
 * Property editor — maps canvas nodes to Field Definitions (no duplicate controls).
 */
final class PropertyEditor
{
    public function definitionFor(CanvasNode $node): FieldDefinition
    {
        return FieldDefinition::fromArray([
            'id' => $node->id,
            'name' => $node->name,
            'type' => $node->type,
            ...$node->settings,
            'conditions' => $node->condition ?? [],
        ]);
    }

    /**
     * @param array<string, mixed> $settings
     */
    public function apply(CanvasNode $node, array $settings): CanvasNode
    {
        return $node->withSettings([...$node->settings, ...$settings]);
    }

    /**
     * @param array<string, mixed>|null $condition
     */
    public function applyCondition(CanvasNode $node, ?array $condition): CanvasNode
    {
        return $node->withCondition($condition);
    }
}
