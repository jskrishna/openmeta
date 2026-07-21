<?php

declare(strict_types=1);

namespace OpenMeta\Ui\Components;

use OpenMeta\Security\Escaping\Escaper;

/**
 * Card / panel component for admin layouts.
 */
final class Card
{
    public static function render(string $title, string $body, ?string $footer = null): string
    {
        $html = '<section class="om-card">';
        $html .= '<header class="om-card__header"><h2>' . Escaper::html($title) . '</h2></header>';
        $html .= '<div class="om-card__body">' . $body . '</div>';

        if ($footer !== null) {
            $html .= '<footer class="om-card__footer">' . $footer . '</footer>';
        }

        return $html . '</section>';
    }
}
