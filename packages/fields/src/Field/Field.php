<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Field;

use OpenMeta\Fields\Contracts\FieldInterface;
use OpenMeta\Support\Arr\Arr;

/**
 * Base field — shared settings, identity, and default sanitize/cast.
 */
abstract class Field implements FieldInterface
{
    /** @param array<string, mixed> $settings */
    public function __construct(
        protected string $name,
        protected array $settings = [],
    ) {
    }

    abstract public function type(): string;

    public function name(): string
    {
        return $this->name;
    }

    public function id(): string
    {
        $id = $this->settings['id'] ?? null;

        return is_string($id) && $id !== '' ? $id : $this->name;
    }

    public function label(): string
    {
        $label = $this->settings['label'] ?? null;

        return is_string($label) && $label !== '' ? $label : $this->name;
    }

    public function description(): string
    {
        $description = $this->settings['description'] ?? null;

        return is_string($description) ? $description : '';
    }

    public function settings(): array
    {
        return $this->settings;
    }

    public function setting(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->settings, $key, $default);
    }

    public function isRequired(): bool
    {
        return (bool) $this->setting('required', false);
    }

    public function isVisible(): bool
    {
        return (bool) $this->setting('visible', true);
    }

    public function isReadonly(): bool
    {
        return (bool) $this->setting('readonly', false);
    }

    public function isDisabled(): bool
    {
        return (bool) $this->setting('disabled', false);
    }

    /**
     * @return array<string, mixed>
     */
    public function conditions(): array
    {
        $conditions = $this->setting('conditions', []);

        return is_array($conditions) ? $conditions : [];
    }

    public function rules(): array|string
    {
        $extra = $this->setting('rules');

        if (is_string($extra) || (is_array($extra) && $extra !== [])) {
            $base = [];

            if ($this->isRequired()) {
                $base[] = 'required';
            } else {
                $base[] = 'nullable';
            }

            if (is_string($extra)) {
                return array_merge($base, [$extra], $this->typeRules());
            }

            /** @var list<string|\OpenMeta\Validation\Contracts\RuleInterface> $extra */
            return array_merge($base, $extra, $this->typeRules());
        }

        $rules = [];

        if ($this->isRequired()) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        return array_merge($rules, $this->typeRules());
    }

    /** @return list<string> */
    abstract protected function typeRules(): array;

    public function sanitize(mixed $value): mixed
    {
        return $value;
    }

    public function cast(mixed $value): mixed
    {
        return $value;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id(),
            'type' => $this->type(),
            'name' => $this->name(),
            'label' => $this->label(),
            'description' => $this->description(),
            'settings' => $this->settings(),
        ];
    }
}
