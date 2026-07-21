<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Contracts;

/**
 * Named ordered collection of field definitions (supports nesting).
 */
interface FieldGroupInterface
{
    public function id(): string;

    public function name(): string;

    public function label(): string;

    public function order(): int;

    public function isVisible(): bool;

    /**
     * @return list<FieldDefinitionInterface>
     */
    public function fields(): array;

    /**
     * @return list<FieldGroupInterface>
     */
    public function groups(): array;

    /**
     * @return array<string, mixed>
     */
    public function conditions(): array;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
