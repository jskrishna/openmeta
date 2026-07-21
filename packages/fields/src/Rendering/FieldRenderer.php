<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Rendering;

use OpenMeta\Fields\Contracts\FieldRendererInterface;
use OpenMeta\Fields\Field\Field;
use OpenMeta\Security\Escaping\Escaper;

/**
 * Default contract renderer — escaped plain descriptors, no HTML / UI markup.
 *
 * Admin / UI packages register richer {@see FieldRendererInterface} implementations
 * that emit components or markup. The Field Engine itself stays presentation-agnostic.
 */
final class FieldRenderer implements FieldRendererInterface
{
    public function render(Field $field, mixed $value, string $context = 'edit'): string
    {
        return $context === 'display'
            ? $this->display($field, $value)
            : $this->edit($field, $value);
    }

    public function edit(Field $field, mixed $value): string
    {
        return sprintf(
            'field[%s] type=%s name=%s label=%s value=%s',
            Escaper::attr($field->id()),
            Escaper::attr($field->type()),
            Escaper::attr($field->name()),
            Escaper::attr($field->label()),
            Escaper::attr($this->stringify($value))
        );
    }

    public function display(Field $field, mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_bool($value)) {
            return $value ? 'yes' : 'no';
        }

        if (is_array($value)) {
            return Escaper::html(implode(', ', array_map(
                static function (mixed $item): string {
                    if (is_scalar($item)) {
                        return (string) $item;
                    }

                    $json = json_encode($item);

                    return $json === false ? '' : $json;
                },
                $value
            )));
        }

        return Escaper::html((string) $value);
    }

    private function stringify(mixed $value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_array($value)) {
            $json = json_encode($value);

            return $json === false ? '' : $json;
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return '';
    }
}
