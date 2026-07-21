<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Forms;

use OpenMeta\Security\Nonce\Nonce;
use OpenMeta\Security\Sanitization\Sanitizer;
use OpenMeta\Ui\Components\Form as UiForm;
use OpenMeta\Ui\Primitives\Input;
use OpenMeta\Ui\Primitives\Notice;
use OpenMeta\Validation\Validation;

/**
 * Admin form pattern — nonce + validation + UI primitives.
 */
final class AdminForm
{
    /**
     * @param list<array{name: string, label: string, type?: string, rules?: string}> $fields
     * @param array<string, mixed> $values
     */
    public function __construct(
        private readonly string $id,
        private readonly array $fields,
        private readonly Nonce $nonce,
        private readonly array $values = [],
        private readonly string $action = '',
    ) {
    }

    public function render(): string
    {
        $fieldsHtml = '';

        foreach ($this->fields as $field) {
            $name = $field['name'];
            $fieldsHtml .= '<div class="om-form__field">'
                . Input::render(
                    $name,
                    $this->values[$name] ?? '',
                    $field['type'] ?? 'text',
                    $field['label']
                )
                . '</div>';
        }

        return UiForm::render(
            $this->action,
            'post',
            $fieldsHtml,
            'Save',
            [
                '_wpnonce' => $this->nonce->create($this->id),
                '_om_form' => $this->id,
            ]
        );
    }

    /**
     * @param array<string, mixed> $input
     * @return array{ok: bool, values: array<string, mixed>, error?: string}
     */
    public function submit(array $input): array
    {
        $token = isset($input['_wpnonce']) ? (string) $input['_wpnonce'] : '';

        if (! $this->nonce->verify($token, $this->id)) {
            return [
                'ok' => false,
                'values' => $this->values,
                'error' => 'Invalid nonce.',
            ];
        }

        $rules = [];
        $data = [];

        foreach ($this->fields as $field) {
            $name = $field['name'];
            $raw = $input[$name] ?? null;
            $data[$name] = Sanitizer::text($raw);
            $rules[$name] = $field['rules'] ?? 'nullable|string';
        }

        $validator = Validation::make($data, $rules);

        if ($validator->fails()) {
            return [
                'ok' => false,
                'values' => $data,
                'error' => (string) $validator->errors()->first(),
            ];
        }

        return ['ok' => true, 'values' => $data];
    }

    public function errorNotice(string $message): string
    {
        return Notice::render($message, 'error');
    }

    public function id(): string
    {
        return $this->id;
    }
}
