<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Layouts;

use OpenMeta\Admin\Contracts\LayoutInterface;
use OpenMeta\Admin\Support\ScreenContext;
use OpenMeta\Security\Escaping\Escaper;

/**
 * Single-column content layout.
 */
final class FullWidthLayout implements LayoutInterface
{
    public function id(): string
    {
        return 'full-width';
    }

    public function render(ScreenContext $context): string
    {
        $html = $this->header($context);
        $html .= '<div class="om-layout om-layout--full">' . $context->content() . '</div>';

        return $html . $this->footer($context);
    }

    private function header(ScreenContext $context): string
    {
        $html = '<header class="om-screen__header">';
        $html .= '<h1>' . Escaper::html($context->title) . '</h1>';

        if ($context->description !== null && $context->description !== '') {
            $html .= '<p class="om-screen__description">' . Escaper::html($context->description) . '</p>';
        }

        $toolbar = $context->toolbar();
        $actions = $context->actions();

        if ($toolbar !== '' || $actions !== '') {
            $html .= '<div class="om-screen__toolbar">' . $toolbar . $actions . '</div>';
        }

        return $html . '</header>';
    }

    private function footer(ScreenContext $context): string
    {
        $footer = $context->footer();

        if ($footer === '') {
            return '';
        }

        return '<footer class="om-screen__footer">' . $footer . '</footer>';
    }
}
