<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Preview;

use OpenMeta\Builder\Canvas\Canvas;
use OpenMeta\Builder\Conditions\ConditionBuilder;
use OpenMeta\Builder\Contracts\PreviewEngineInterface;
use OpenMeta\Fields\Definitions\FieldDefinition;
use OpenMeta\Fields\FieldEngine;

/**
 * Preview engine — produces portable preview descriptors, not HTML.
 */
final class PreviewEngine implements PreviewEngineInterface
{
    public function __construct(
        private readonly FieldEngine $fields,
        private readonly ConditionBuilder $conditions,
    ) {
    }

    public function generate(Canvas $canvas, array $values = []): PreviewResult
    {
        $nodes = $canvas->nodes();
        $visible = $this->conditions->visibleNodes($nodes, $values);
        $fields = [];

        foreach ($visible as $node) {
            $field = $this->fields->make($node->type, $node->name, $node->settings);
            $fields[] = [
                'id' => $node->id,
                'name' => $node->name,
                'type' => $node->type,
                'definition' => FieldDefinition::fromArray([
                    'id' => $node->id,
                    'name' => $node->name,
                    'type' => $node->type,
                    ...$node->settings,
                    'conditions' => $node->condition ?? [],
                ])->toArray(),
                'value' => $values[$node->name] ?? null,
            ];
        }

        return new PreviewResult($fields, count($nodes), count($visible));
    }
}
