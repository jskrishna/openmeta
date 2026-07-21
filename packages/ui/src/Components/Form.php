<?php

declare(strict_types=1);

namespace OpenMeta\Ui\Components;

use OpenMeta\Security\Escaping\Escaper;
use OpenMeta\Ui\Primitives\Button;
use OpenMeta\Ui\Primitives\Input;

/**
 * Form shell component — fields HTML injected by callers.
 */
final class Form
{
    /**
     * @param array<string, string> $hidden
     */
    public static function render(
        string $action,
        string $method,
        string $fieldsHtml,
        string $submitLabel = 'Save',
        array $hidden = [],
    ): string {
        $html = '<form class="om-form" method="' . Escaper::attr($method) . '" action="'
            . Escaper::attr($action) . '">';

        foreach ($hidden as $name => $value) {
            $html .= Input::render($name, $value, 'hidden');
        }

        $html .= '<div class="om-form__fields">' . $fieldsHtml . '</div>';
        $html .= '<div class="om-form__actions">' . Button::render($submitLabel, 'submit') . '</div>';

        return $html . '</form>';
    }
}
