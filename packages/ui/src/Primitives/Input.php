<?php

declare(strict_types=1);

namespace OpenMeta\Ui\Primitives;

use OpenMeta\Security\Escaping\Escaper;

/**
 * Accessibility-first input primitive.
 */
final class Input
{
    /**
     * @param array<string, string> $attributes
     */
    public static function render(
        string $name,
        mixed $value = '',
        string $type = 'text',
        ?string $label = null,
        array $attributes = [],
    ): string {
        $id = $attributes['id'] ?? $name;
        $attrs = array_merge([
            'id' => $id,
            'name' => $name,
            'type' => $type,
            'value' => is_scalar($value) || $value === null ? (string) $value : '',
            'class' => 'om-input',
        ], $attributes);

        $html = '';

        if ($label !== null) {
            $html .= '<label class="om-label" for="' . Escaper::attr($id) . '">'
                . Escaper::html($label) . '</label>';
        }

        $html .= '<input';
        foreach ($attrs as $key => $val) {
            $html .= ' ' . Escaper::attr($key) . '="' . Escaper::attr($val) . '"';
        }
        $html .= ' />';

        return $html;
    }
}
