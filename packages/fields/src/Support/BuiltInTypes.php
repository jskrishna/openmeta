<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Support;

/**
 * Built-in type → class map used during provider boot / discovery.
 */
final class BuiltInTypes
{
    /**
     * @return array<string, class-string<\OpenMeta\Fields\Field\Field>>
     */
    public static function map(): array
    {
        return [
            'text' => \OpenMeta\Fields\Types\TextField::class,
            'textarea' => \OpenMeta\Fields\Types\TextareaField::class,
            'number' => \OpenMeta\Fields\Types\NumberField::class,
            'email' => \OpenMeta\Fields\Types\EmailField::class,
            'url' => \OpenMeta\Fields\Types\UrlField::class,
            'password' => \OpenMeta\Fields\Types\PasswordField::class,
            'hidden' => \OpenMeta\Fields\Types\HiddenField::class,
            'checkbox' => \OpenMeta\Fields\Types\CheckboxField::class,
            'radio' => \OpenMeta\Fields\Types\RadioField::class,
            'select' => \OpenMeta\Fields\Types\SelectField::class,
            'multiselect' => \OpenMeta\Fields\Types\MultiSelectField::class,
            'toggle' => \OpenMeta\Fields\Types\ToggleField::class,
            'boolean' => \OpenMeta\Fields\Types\BooleanField::class,
            'date' => \OpenMeta\Fields\Types\DateField::class,
            'datetime' => \OpenMeta\Fields\Types\DateTimeField::class,
            'time' => \OpenMeta\Fields\Types\TimeField::class,
            'color' => \OpenMeta\Fields\Types\ColorField::class,
            'range' => \OpenMeta\Fields\Types\RangeField::class,
            'file' => \OpenMeta\Fields\Types\FileField::class,
            'image' => \OpenMeta\Fields\Types\ImageField::class,
            'gallery' => \OpenMeta\Fields\Types\GalleryField::class,
            'relationship' => \OpenMeta\Fields\Types\RelationshipField::class,
            'repeater' => \OpenMeta\Fields\Types\RepeaterField::class,
            'group' => \OpenMeta\Fields\Types\GroupField::class,
            'object' => \OpenMeta\Fields\Types\ObjectField::class,
        ];
    }
}
