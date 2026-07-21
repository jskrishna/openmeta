<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Layouts;

use OpenMeta\Admin\Contracts\LayoutInterface;
use OpenMeta\Admin\Support\ScreenContext;
use OpenMeta\Security\Escaping\Escaper;

/**
 * Main content + sidebar column layout.
 */
final class SidebarLayout implements LayoutInterface
{
    public function id(): string
    {
        return 'sidebar';
    }

    public function render(ScreenContext $context): string
    {
        $html = '<header class="om-screen__header"><h1>' . Escaper::html($context->title) . '</h1></header>';
        $html .= '<div class="om-layout om-layout--sidebar">';
        $html .= '<main class="om-layout__main">' . $context->content() . '</main>';
        $html .= '<aside class="om-layout__sidebar">' . $context->sidebar() . '</aside>';
        $html .= '</div>';

        return $html;
    }
}
