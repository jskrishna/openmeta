<?php

declare(strict_types=1);

namespace OpenMeta\Ui\Primitives;

use OpenMeta\Security\Escaping\Escaper;

/**
 * Accessibility-first button primitive.
 */
final class Button
{
    /**
     * @param array<string, string> $attributes
     */
    public static function render(
        string $label,
        string $type = 'button',
        string $variant = 'primary',
        array $attributes = [],
    ): string {
        $attrs = self::attrs(array_merge([
            'type' => $type,
            'class' => 'om-btn om-btn--' . $variant,
        ], $attributes));

        return '<button' . $attrs . '>' . Escaper::html($label) . '</button>';
    }

    /**
     * @param array<string, string> $attributes
     */
    private static function attrs(array $attributes): string
    {
        $html = '';

        foreach ($attributes as $key => $value) {
            $html .= ' ' . Escaper::attr($key) . '="' . Escaper::attr($value) . '"';
        }

        return $html;
    }
}
