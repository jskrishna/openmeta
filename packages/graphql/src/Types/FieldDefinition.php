<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Types;

/**
 * A field on an object/interface type, or a root query/mutation field.
 *
 * The optional `resolver`, `permission`, and `rules` metadata drive the
 * execution pipeline (resolver lookup, authorization, input validation).
 */
final class FieldDefinition
{
    /**
     * @param list<ArgumentDefinition>    $args
     * @param array<string, list<string>> $rules      arg name => validation rules
     */
    public function __construct(
        public readonly string $name,
        public readonly TypeReference $type,
        public readonly string $description = '',
        public readonly array $args = [],
        public readonly ?string $resolver = null,
        public readonly ?string $permission = null,
        public readonly array $rules = [],
        public readonly ?string $deprecationReason = null,
        public readonly ?string $group = null,
    ) {
    }

    public function isDeprecated(): bool
    {
        return $this->deprecationReason !== null;
    }

    public function toSdl(): string
    {
        $args = '';

        if ($this->args !== []) {
            $args = '(' . implode(', ', array_map(
                static fn (ArgumentDefinition $arg): string => $arg->toSdl(),
                $this->args
            )) . ')';
        }

        return $this->name . $args . ': ' . $this->type->toSdl();
    }
}
