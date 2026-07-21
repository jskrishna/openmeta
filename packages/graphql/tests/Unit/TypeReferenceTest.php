<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Tests\Unit;

use OpenMeta\GraphQL\Types\TypeReference;
use PHPUnit\Framework\TestCase;

final class TypeReferenceTest extends TestCase
{
    public function test_named(): void
    {
        self::assertSame('String', TypeReference::named('String')->toSdl());
    }

    public function test_non_null(): void
    {
        self::assertSame('ID!', TypeReference::named('ID')->nonNull()->toSdl());
    }

    public function test_list(): void
    {
        self::assertSame('[Post]', TypeReference::named('Post')->listOf()->toSdl());
    }

    public function test_list_of_non_null(): void
    {
        self::assertSame('[Post!]', TypeReference::named('Post')->listOf(true)->toSdl());
    }

    public function test_non_null_list_of_non_null(): void
    {
        self::assertSame('[Post!]!', TypeReference::named('Post')->listOf(true)->nonNull()->toSdl());
    }

    public function test_base_name_ignores_wrappers(): void
    {
        self::assertSame('Post', TypeReference::named('Post')->listOf(true)->nonNull()->baseName());
    }
}
