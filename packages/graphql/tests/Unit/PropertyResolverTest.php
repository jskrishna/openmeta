<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Tests\Unit;

use OpenMeta\GraphQL\Resolvers\PropertyResolver;
use OpenMeta\GraphQL\Resolvers\ResolutionContext;
use PHPUnit\Framework\TestCase;

final class PropertyResolverTest extends TestCase
{
    public function test_resolves_array_key(): void
    {
        $resolver = new PropertyResolver('title');

        self::assertSame('Hi', $resolver->resolve(['title' => 'Hi'], [], new ResolutionContext()));
    }

    public function test_resolves_getter(): void
    {
        $root = new class {
            public function getTitle(): string
            {
                return 'From getter';
            }
        };

        self::assertSame('From getter', (new PropertyResolver('title'))->resolve($root, [], new ResolutionContext()));
    }

    public function test_resolves_public_property(): void
    {
        $root = new class {
            public string $title = 'From property';
        };

        self::assertSame('From property', (new PropertyResolver('title'))->resolve($root, [], new ResolutionContext()));
    }

    public function test_returns_null_when_absent(): void
    {
        self::assertNull((new PropertyResolver('title'))->resolve(['other' => 1], [], new ResolutionContext()));
    }
}
