<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Toolbar;

use OpenMeta\Security\Escaping\Escaper;
use OpenMeta\Ui\Primitives\Button;

/**
 * Screen toolbar — actions, search, filters.
 */
final class Toolbar
{
    /** @var list<array{label: string, action: string}> */
    private array $actions = [];

    private string $searchPlaceholder = '';

    /** @var list<array{id: string, label: string}> */
    private array $filters = [];

    public function action(string $label, string $action): self
    {
        $this->actions[] = ['label' => $label, 'action' => $action];

        return $this;
    }

    public function search(string $placeholder): self
    {
        $this->searchPlaceholder = $placeholder;

        return $this;
    }

    public function filter(string $id, string $label): self
    {
        $this->filters[] = ['id' => $id, 'label' => $label];

        return $this;
    }

    public function render(): string
    {
        $html = '<div class="om-toolbar">';

        foreach ($this->actions as $action) {
            $html .= Button::render($action['label'], 'button', 'primary', [
                'data-action' => $action['action'],
            ]);
        }

        if ($this->searchPlaceholder !== '') {
            $html .= '<input type="search" class="om-toolbar__search" placeholder="'
                . Escaper::attr($this->searchPlaceholder) . '" />';
        }

        foreach ($this->filters as $filter) {
            $html .= '<button type="button" class="om-toolbar__filter" data-filter="'
                . Escaper::attr($filter['id']) . '">'
                . Escaper::html($filter['label'])
                . '</button>';
        }

        return $html . '</div>';
    }
}
