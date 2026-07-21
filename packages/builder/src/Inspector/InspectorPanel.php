<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Inspector;

use OpenMeta\Builder\Canvas\CanvasNode;
use OpenMeta\Builder\Properties\PropertyEditor;

/**
 * Inspector panel metadata — labels, settings, validation, conditions, visibility, styles.
 */
final class InspectorPanel
{
    public function __construct(private readonly PropertyEditor $properties)
    {
    }

    /** @return array<string, mixed> */
    public function describe(CanvasNode $node): array
    {
        $definition = $this->properties->definitionFor($node);

        return [
            'node_id' => $node->id,
            'label' => $definition->label() !== '' ? $definition->label() : $node->name,
            'settings' => $node->settings,
            'validation_rules' => $definition->validationRules(),
            'conditions' => $node->condition,
            'visibility' => [
                'visible' => $definition->isVisible(),
                'readonly' => $definition->isReadonly(),
                'disabled' => $definition->isDisabled(),
            ],
            'styles' => is_array($node->settings['styles'] ?? null) ? $node->settings['styles'] : [],
        ];
    }
}
