<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Tabs;

use OpenMeta\Security\Escaping\Escaper;

/**
 * Tabbed panel navigation (presentation shell).
 */
final class TabGroup
{
    /** @var list<array{id: string, label: string, content: string}> */
    private array $tabs = [];

    private string $activeId = '';

    public function tab(string $id, string $label, string $content): self
    {
        $this->tabs[] = ['id' => $id, 'label' => $label, 'content' => $content];

        if ($this->activeId === '') {
            $this->activeId = $id;
        }

        return $this;
    }

    public function active(string $id): self
    {
        $this->activeId = $id;

        return $this;
    }

    public function render(): string
    {
        $html = '<div class="om-tabs"><ul class="om-tabs__nav" role="tablist">';

        foreach ($this->tabs as $tab) {
            $active = $tab['id'] === $this->activeId ? ' om-tabs__tab--active' : '';
            $html .= '<li role="presentation"><button type="button" role="tab" class="om-tabs__tab'
                . $active . '" data-tab="' . Escaper::attr($tab['id']) . '">'
                . Escaper::html($tab['label']) . '</button></li>';
        }

        $html .= '</ul><div class="om-tabs__panels">';

        foreach ($this->tabs as $tab) {
            $hidden = $tab['id'] === $this->activeId ? '' : ' hidden';
            $html .= '<div class="om-tabs__panel' . $hidden . '" data-tab-panel="'
                . Escaper::attr($tab['id']) . '">' . $tab['content'] . '</div>';
        }

        return $html . '</div></div>';
    }
}
