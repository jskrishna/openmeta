<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Forms;

use OpenMeta\Admin\Events\FormSubmitted;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Fields\Contracts\FieldRendererInterface;
use OpenMeta\Fields\Field\Field;
use OpenMeta\Security\Nonce\Nonce;
use OpenMeta\Security\Escaping\Escaper;
use OpenMeta\Security\Sanitization\Sanitizer;
use OpenMeta\Validation\Validation;

/**
 * Form builder — sections, groups, validation, readonly mode.
 */
final class FormBuilder
{
    /** @var list<array{id: string, title: string, groups: list<array{id: string, fields: list<array<string, mixed>|Field>}>}> */
    private array $sections = [];

    private bool $readonly = false;

    public function __construct(
        private readonly string $id,
        private readonly Nonce $nonce,
        private readonly ?FieldRendererInterface $fieldRenderer = null,
        private readonly ?EventDispatcherInterface $events = null,
    ) {
    }

    public function section(string $id, string $title): self
    {
        $this->sections[] = ['id' => $id, 'title' => $title, 'groups' => []];

        return $this;
    }

    public function group(string $sectionId, string $groupId): self
    {
        foreach ($this->sections as &$section) {
            if ($section['id'] !== $sectionId) {
                continue;
            }

            $section['groups'][] = ['id' => $groupId, 'fields' => []];
        }

        return $this;
    }

    /**
     * @param array{name: string, label: string, type?: string, rules?: string} $field
     */
    public function field(string $sectionId, string $groupId, array $field): self
    {
        foreach ($this->sections as &$section) {
            if ($section['id'] !== $sectionId) {
                continue;
            }

            foreach ($section['groups'] as &$group) {
                if ($group['id'] === $groupId) {
                    $group['fields'][] = $field;
                }
            }
        }

        return $this;
    }

    public function fieldModel(string $sectionId, string $groupId, Field $field, mixed $value): self
    {
        foreach ($this->sections as &$section) {
            if ($section['id'] !== $sectionId) {
                continue;
            }

            foreach ($section['groups'] as &$group) {
                if ($group['id'] === $groupId) {
                    $group['fields'][] = ['model' => $field, 'value' => $value];
                }
            }
        }

        return $this;
    }

    public function readonly(bool $readonly = true): self
    {
        $this->readonly = $readonly;

        return $this;
    }

    /**
     * @param array<string, mixed> $values
     */
    public function render(array $values = [], string $action = ''): string
    {
        $flatFields = $this->flatFieldDefinitions();
        $form = new AdminForm($this->id, $flatFields, $this->nonce, $values, $action);

        $html = '';

        foreach ($this->sections as $section) {
            $html .= '<section class="om-form__section" data-section="' . $section['id'] . '">';
            $html .= '<h2 class="om-form__section-title">' . Escaper::html($section['title']) . '</h2>';

            foreach ($section['groups'] as $group) {
                $html .= '<div class="om-form__group" data-group="' . $group['id'] . '">';

                foreach ($group['fields'] as $fieldDef) {
                    if (isset($fieldDef['model']) && $this->fieldRenderer !== null) {
                        /** @var Field $model */
                        $model = $fieldDef['model'];
                        $context = $this->readonly ? 'display' : 'edit';
                        $html .= '<div class="om-form__field">'
                            . $this->fieldRenderer->render($model, $fieldDef['value'] ?? null, $context)
                            . '</div>';
                    }
                }

                $html .= '</div>';
            }

            $html .= '</section>';
        }

        return $html . $form->render();
    }

    /**
     * @param array<string, mixed> $input
     * @return array{ok: bool, values: array<string, mixed>, error?: string}
     */
    public function submit(array $input): array
    {
        $form = new AdminForm($this->id, $this->flatFieldDefinitions(), $this->nonce);
        $result = $form->submit($input);
        $this->events?->dispatch(new FormSubmitted(
            $this->id,
            $result['ok'],
            $result['values'],
        ));

        return $result;
    }

    /**
     * @return list<array{name: string, label: string, type?: string, rules?: string}>
     */
    private function flatFieldDefinitions(): array
    {
        $fields = [];

        foreach ($this->sections as $section) {
            foreach ($section['groups'] as $group) {
                foreach ($group['fields'] as $fieldDef) {
                    if (isset($fieldDef['name'])) {
                        $fields[] = $fieldDef;
                    }
                }
            }
        }

        return $fields;
    }

    public function id(): string
    {
        return $this->id;
    }
}
