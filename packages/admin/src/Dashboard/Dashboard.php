<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Dashboard;

use OpenMeta\Security\Escaping\Escaper;
use OpenMeta\Ui\Components\Card;

/**
 * OpenMeta home / overview screen content.
 */
final class Dashboard
{
    public const SCREEN_ID = 'openmeta-dashboard';

    /** @var array<string, callable(): string> */
    private array $widgets = [];

    public function registerWidget(string $id, callable $renderer): void
    {
        $this->widgets[$id] = $renderer;
    }

    /** @return list<string> */
    public function widgetIds(): array
    {
        return array_keys($this->widgets);
    }

    public function render(): string
    {
        $body = '<p class="om-dashboard__intro">'
            . Escaper::html('Welcome to OpenMeta.')
            . '</p>';

        foreach ($this->widgets as $id => $renderer) {
            $body .= '<div class="om-dashboard__widget" data-widget="'
                . Escaper::attr($id) . '">' . $renderer() . '</div>';
        }

        return Card::render('OpenMeta', $body);
    }
}
