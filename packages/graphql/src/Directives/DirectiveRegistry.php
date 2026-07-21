<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Directives;

use OpenMeta\GraphQL\Errors\DuplicateTypeException;
use OpenMeta\GraphQL\Errors\TypeNotFoundException;

/**
 * Registry of directive definitions.
 */
final class DirectiveRegistry
{
    /** @var array<string, DirectiveDefinition> */
    private array $directives = [];

    /**
     * Seed the spec directives: @deprecated, @skip, @include.
     */
    public function registerDefaults(): void
    {
        $this->directives['deprecated'] ??= new DirectiveDefinition(
            'deprecated',
            ['FIELD_DEFINITION', 'ENUM_VALUE'],
            'Marks an element as deprecated.',
        );
        $this->directives['skip'] ??= new DirectiveDefinition(
            'skip',
            ['FIELD', 'FRAGMENT_SPREAD', 'INLINE_FRAGMENT'],
            'Conditionally skips a field.',
        );
        $this->directives['include'] ??= new DirectiveDefinition(
            'include',
            ['FIELD', 'FRAGMENT_SPREAD', 'INLINE_FRAGMENT'],
            'Conditionally includes a field.',
        );
    }

    public function register(DirectiveDefinition $directive): void
    {
        if (isset($this->directives[$directive->name])) {
            throw DuplicateTypeException::named('Directive', $directive->name);
        }

        $this->directives[$directive->name] = $directive;
    }

    public function has(string $name): bool
    {
        return isset($this->directives[$name]);
    }

    public function get(string $name): DirectiveDefinition
    {
        return $this->directives[$name] ?? throw TypeNotFoundException::named($name);
    }

    /**
     * @return list<DirectiveDefinition>
     */
    public function all(): array
    {
        return array_values($this->directives);
    }
}
