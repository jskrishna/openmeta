<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Definitions;

use OpenMeta\Fields\Contracts\FieldDefinitionInterface;
use OpenMeta\Fields\Exceptions\InvalidDefinitionException;

/**
 * Immutable field definition — withers return new instances.
 */
final class FieldDefinition implements FieldDefinitionInterface
{
    /**
     * @param list<string|\OpenMeta\Validation\Contracts\RuleInterface>|string $validationRules
     * @param array<string, mixed> $conditions
     * @param array<string, mixed> $metadata
     * @param array<string, mixed> $attributes
     */
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $type,
        private readonly string $label = '',
        private readonly string $description = '',
        private readonly mixed $defaultValue = null,
        private readonly bool $required = false,
        private readonly array|string $validationRules = [],
        private readonly array $conditions = [],
        private readonly bool $visible = true,
        private readonly bool $readonly = false,
        private readonly bool $disabled = false,
        private readonly array $metadata = [],
        private readonly array $attributes = [],
    ) {
        if ($this->id === '' || $this->name === '' || $this->type === '') {
            throw new InvalidDefinitionException(
                'Field definition requires non-empty id, name, and type.'
            );
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $id = isset($data['id']) && is_string($data['id']) ? $data['id'] : '';
        $name = isset($data['name']) && is_string($data['name']) ? $data['name'] : '';
        $type = isset($data['type']) && is_string($data['type']) ? $data['type'] : '';

        if ($id === '' && $name !== '') {
            $id = $name;
        }

        /** @var list<string|\OpenMeta\Validation\Contracts\RuleInterface>|string $rules */
        $rules = $data['validation_rules'] ?? $data['rules'] ?? [];

        return new self(
            id: $id,
            name: $name,
            type: $type,
            label: isset($data['label']) && is_string($data['label']) ? $data['label'] : '',
            description: isset($data['description']) && is_string($data['description']) ? $data['description'] : '',
            defaultValue: $data['default'] ?? $data['default_value'] ?? null,
            required: (bool) ($data['required'] ?? false),
            validationRules: is_array($rules) || is_string($rules) ? $rules : [],
            conditions: isset($data['conditions']) && is_array($data['conditions']) ? $data['conditions'] : [],
            visible: (bool) ($data['visible'] ?? true),
            readonly: (bool) ($data['readonly'] ?? false),
            disabled: (bool) ($data['disabled'] ?? false),
            metadata: isset($data['metadata']) && is_array($data['metadata']) ? $data['metadata'] : [],
            attributes: isset($data['attributes']) && is_array($data['attributes']) ? $data['attributes'] : [],
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function label(): string
    {
        return $this->label !== '' ? $this->label : $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function defaultValue(): mixed
    {
        return $this->defaultValue;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function validationRules(): array|string
    {
        return $this->validationRules;
    }

    public function conditions(): array
    {
        return $this->conditions;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function isReadonly(): bool
    {
        return $this->readonly;
    }

    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    public function metadata(): array
    {
        return $this->metadata;
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    public function withLabel(string $label): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->type,
            $label,
            $this->description,
            $this->defaultValue,
            $this->required,
            $this->validationRules,
            $this->conditions,
            $this->visible,
            $this->readonly,
            $this->disabled,
            $this->metadata,
            $this->attributes,
        );
    }

    public function withRequired(bool $required): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->type,
            $this->label,
            $this->description,
            $this->defaultValue,
            $required,
            $this->validationRules,
            $this->conditions,
            $this->visible,
            $this->readonly,
            $this->disabled,
            $this->metadata,
            $this->attributes,
        );
    }

    public function withDefaultValue(mixed $defaultValue): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->type,
            $this->label,
            $this->description,
            $defaultValue,
            $this->required,
            $this->validationRules,
            $this->conditions,
            $this->visible,
            $this->readonly,
            $this->disabled,
            $this->metadata,
            $this->attributes,
        );
    }

    /**
     * @param list<string|\OpenMeta\Validation\Contracts\RuleInterface>|string $rules
     */
    public function withValidationRules(array|string $rules): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->type,
            $this->label,
            $this->description,
            $this->defaultValue,
            $this->required,
            $rules,
            $this->conditions,
            $this->visible,
            $this->readonly,
            $this->disabled,
            $this->metadata,
            $this->attributes,
        );
    }

    /**
     * @param array<string, mixed> $conditions
     */
    public function withConditions(array $conditions): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->type,
            $this->label,
            $this->description,
            $this->defaultValue,
            $this->required,
            $this->validationRules,
            $conditions,
            $this->visible,
            $this->readonly,
            $this->disabled,
            $this->metadata,
            $this->attributes,
        );
    }

    /**
     * Settings bag consumed by Field types / factory.
     *
     * @return array<string, mixed>
     */
    public function toSettings(): array
    {
        $settings = [
            'id' => $this->id,
            'label' => $this->label(),
            'description' => $this->description,
            'required' => $this->required,
            'default' => $this->defaultValue,
            'visible' => $this->visible,
            'readonly' => $this->readonly,
            'disabled' => $this->disabled,
            'conditions' => $this->conditions,
            'metadata' => $this->metadata,
            'attributes' => $this->attributes,
        ];

        if ($this->validationRules !== [] && $this->validationRules !== '') {
            $settings['rules'] = $this->validationRules;
        }

        return array_merge($this->attributes, $settings);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'label' => $this->label(),
            'description' => $this->description,
            'default' => $this->defaultValue,
            'required' => $this->required,
            'validation_rules' => $this->validationRules,
            'conditions' => $this->conditions,
            'visible' => $this->visible,
            'readonly' => $this->readonly,
            'disabled' => $this->disabled,
            'metadata' => $this->metadata,
            'attributes' => $this->attributes,
        ];
    }
}
