<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Types;

/**
 * Fluent builder for object types.
 *
 *   TypeBuilder::object('Post')
 *       ->field('id', TypeReference::named('ID')->nonNull())
 *       ->field('title', TypeReference::named('String'))
 *       ->build();
 */
final class TypeBuilder
{
    /** @var list<FieldDefinition> */
    private array $fields = [];

    /** @var list<string> */
    private array $interfaces = [];

    private string $description = '';

    private function __construct(private readonly string $name)
    {
    }

    public static function object(string $name): self
    {
        return new self($name);
    }

    public function describe(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param list<ArgumentDefinition> $args
     */
    public function field(
        string $name,
        TypeReference $type,
        string $description = '',
        array $args = [],
        ?string $resolver = null,
        ?string $permission = null,
    ): self {
        $this->fields[] = new FieldDefinition(
            $name,
            $type,
            $description,
            $args,
            $resolver,
            $permission,
        );

        return $this;
    }

    public function addField(FieldDefinition $field): self
    {
        $this->fields[] = $field;

        return $this;
    }

    public function implements(string $interface): self
    {
        $this->interfaces[] = $interface;

        return $this;
    }

    public function build(): ObjectType
    {
        return new ObjectType($this->name, $this->fields, $this->description, $this->interfaces);
    }
}
