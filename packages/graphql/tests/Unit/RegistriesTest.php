<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Tests\Unit;

use OpenMeta\GraphQL\Errors\DuplicateTypeException;
use OpenMeta\GraphQL\Errors\TypeNotFoundException;
use OpenMeta\GraphQL\Scalars\ScalarRegistry;
use OpenMeta\GraphQL\Types\ObjectType;
use OpenMeta\GraphQL\Types\TypeRegistry;
use PHPUnit\Framework\TestCase;

final class RegistriesTest extends TestCase
{
    public function test_type_registry_register_and_get(): void
    {
        $registry = new TypeRegistry();
        $registry->register(new ObjectType('Post', []));

        self::assertTrue($registry->has('Post'));
        self::assertSame('Post', $registry->get('Post')->name);
        self::assertCount(1, $registry->all());
    }

    public function test_duplicate_type_throws(): void
    {
        $registry = new TypeRegistry();
        $registry->register(new ObjectType('Post', []));

        $this->expectException(DuplicateTypeException::class);
        $registry->register(new ObjectType('Post', []));
    }

    public function test_unknown_type_throws(): void
    {
        $this->expectException(TypeNotFoundException::class);
        (new TypeRegistry())->get('Nope');
    }

    public function test_scalar_defaults(): void
    {
        $registry = new ScalarRegistry();
        $registry->registerDefaults();

        foreach (ScalarRegistry::BUILT_IN as $scalar) {
            self::assertTrue($registry->has($scalar));
            self::assertTrue($registry->isBuiltIn($scalar));
        }

        self::assertFalse($registry->isBuiltIn('DateTime'));
    }
}
