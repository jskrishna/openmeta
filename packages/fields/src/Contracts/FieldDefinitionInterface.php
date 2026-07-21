<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Contracts;

/**
 * Immutable field definition contract.
 */
interface FieldDefinitionInterface
{
    public function id(): string;

    public function name(): string;

    public function label(): string;

    public function description(): string;

    public function type(): string;

    public function defaultValue(): mixed;

    public function isRequired(): bool;

    /**
     * @return list<string|\OpenMeta\Validation\Contracts\RuleInterface>|string
     */
    public function validationRules(): array|string;

    /**
     * @return array<string, mixed>
     */
    public function conditions(): array;

    public function isVisible(): bool;

    public function isReadonly(): bool;

    public function isDisabled(): bool;

    /**
     * @return array<string, mixed>
     */
    public function metadata(): array;

    /**
     * @return array<string, mixed>
     */
    public function attributes(): array;

    /**
     * Settings bag for field type construction.
     *
     * @return array<string, mixed>
     */
    public function toSettings(): array;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
