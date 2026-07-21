<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Layouts;

use OpenMeta\Admin\Contracts\LayoutInterface;
use OpenMeta\Admin\Support\ScreenContext;
use OpenMeta\Security\Escaping\Escaper;

/**
 * Dashboard grid layout for widgets.
 */
final class DashboardGridLayout implements LayoutInterface
{
    public function id(): string
    {
        return 'dashboard-grid';
    }

    public function render(ScreenContext $context): string
    {
        $html = '<header class="om-screen__header"><h1>' . Escaper::html($context->title) . '</h1></header>';
        $html .= '<div class="om-layout om-layout--dashboard-grid">' . $context->content() . '</div>';

        return $html;
    }
}
