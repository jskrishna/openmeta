<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Groups;

use OpenMeta\Fields\Contracts\FieldDefinitionInterface;
use OpenMeta\Fields\Contracts\FieldGroupInterface;
use OpenMeta\Fields\Definitions\FieldDefinition;
use OpenMeta\Fields\Exceptions\InvalidDefinitionException;

/**
 * Ordered field group with optional nested groups and conditions.
 */
final class FieldGroup implements FieldGroupInterface
{
    /**
     * @param list<FieldDefinitionInterface> $fields
     * @param list<FieldGroupInterface> $groups
     * @param array<string, mixed> $conditions
     */
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $label = '',
        private readonly int $order = 0,
        private readonly bool $visible = true,
        private readonly array $fields = [],
        private readonly array $groups = [],
        private readonly array $conditions = [],
    ) {
        if ($this->id === '' || $this->name === '') {
            throw new InvalidDefinitionException('Field group requires non-empty id and name.');
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $id = isset($data['id']) && is_string($data['id']) ? $data['id'] : '';
        $name = isset($data['name']) && is_string($data['name']) ? $data['name'] : $id;

        $fields = [];

        foreach ($data['fields'] ?? [] as $field) {
            if (is_array($field)) {
                $fields[] = FieldDefinition::fromArray($field);
            }
        }

        $groups = [];

        foreach ($data['groups'] ?? [] as $group) {
            if (is_array($group)) {
                $groups[] = self::fromArray($group);
            }
        }

        return new self(
            id: $id,
            name: $name,
            label: isset($data['label']) && is_string($data['label']) ? $data['label'] : $name,
            order: isset($data['order']) && is_numeric($data['order']) ? (int) $data['order'] : 0,
            visible: (bool) ($data['visible'] ?? true),
            fields: $fields,
            groups: $groups,
            conditions: isset($data['conditions']) && is_array($data['conditions']) ? $data['conditions'] : [],
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

    public function order(): int
    {
        return $this->order;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function fields(): array
    {
        return $this->fields;
    }

    public function groups(): array
    {
        return $this->groups;
    }

    public function conditions(): array
    {
        return $this->conditions;
    }

    public function withField(FieldDefinitionInterface $field): self
    {
        $fields = $this->fields;
        $fields[] = $field;

        return new self(
            $this->id,
            $this->name,
            $this->label,
            $this->order,
            $this->visible,
            $fields,
            $this->groups,
            $this->conditions,
        );
    }

    public function withGroup(FieldGroupInterface $group): self
    {
        $groups = $this->groups;
        $groups[] = $group;

        return new self(
            $this->id,
            $this->name,
            $this->label,
            $this->order,
            $this->visible,
            $this->fields,
            $groups,
            $this->conditions,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'label' => $this->label(),
            'order' => $this->order,
            'visible' => $this->visible,
            'conditions' => $this->conditions,
            'fields' => array_map(
                static fn (FieldDefinitionInterface $f): array => $f->toArray(),
                $this->fields
            ),
            'groups' => array_map(
                static fn (FieldGroupInterface $g): array => $g->toArray(),
                $this->groups
            ),
        ];
    }
}
