<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Layouts;

use OpenMeta\Admin\Contracts\LayoutInterface;
use OpenMeta\Admin\Support\ScreenContext;
use OpenMeta\Security\Escaping\Escaper;

/**
 * Multi-step wizard shell (content only; steps owned by consumer).
 */
final class WizardLayout implements LayoutInterface
{
    public function id(): string
    {
        return 'wizard';
    }

    public function render(ScreenContext $context): string
    {
        $html = '<div class="om-layout om-layout--wizard">';
        $html .= '<header class="om-wizard__header"><h1>' . Escaper::html($context->title) . '</h1></header>';
        $html .= '<div class="om-wizard__steps">' . $context->toolbar() . '</div>';
        $html .= '<div class="om-wizard__body">' . $context->content() . '</div>';
        $html .= '<footer class="om-wizard__footer">' . $context->footer() . '</footer>';
        $html .= '</div>';

        return $html;
    }
}
