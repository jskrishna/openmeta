<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Layouts;

use OpenMeta\Admin\Contracts\LayoutInterface;
use OpenMeta\Admin\Support\ScreenContext;
use OpenMeta\Security\Escaping\Escaper;

/**
 * Two-column split layout.
 */
final class SplitLayout implements LayoutInterface
{
    public function id(): string
    {
        return 'split';
    }

    public function render(ScreenContext $context): string
    {
        $html = '<header class="om-screen__header"><h1>' . Escaper::html($context->title) . '</h1></header>';
        $html .= '<div class="om-layout om-layout--split">';
        $html .= '<div class="om-layout__pane">' . $context->content() . '</div>';
        $html .= '<div class="om-layout__pane">' . $context->sidebar() . '</div>';
        $html .= '</div>';

        return $html;
    }
}
