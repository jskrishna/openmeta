<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Contracts;

/**
 * Shared contract every field type implements.
 */
interface FieldInterface
{
    public function type(): string;

    public function name(): string;

    public function label(): string;

    /** @return array<string, mixed> */
    public function settings(): array;

    /**
     * Validation rules for {@see \OpenMeta\Validation\Validation}.
     *
     * @return list<string|\OpenMeta\Validation\Contracts\RuleInterface>|string
     */
    public function rules(): array|string;

    public function sanitize(mixed $value): mixed;

    public function cast(mixed $value): mixed;

    /** @return array<string, mixed> */
    public function toArray(): array;
}
