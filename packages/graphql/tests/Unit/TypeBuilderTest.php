<?php

declare(strict_types=1);

namespace OpenMeta\GraphQL\Tests\Unit;

use OpenMeta\GraphQL\Types\TypeBuilder;
use OpenMeta\GraphQL\Types\TypeReference;
use PHPUnit\Framework\TestCase;

final class TypeBuilderTest extends TestCase
{
    public function test_builds_object_type_with_fields_and_interfaces(): void
    {
        $type = TypeBuilder::object('Post')
            ->describe('A blog post')
            ->field('id', TypeReference::named('ID')->nonNull())
            ->field('title', TypeReference::named('String'))
            ->implements('Node')
            ->build();

        self::assertSame('Post', $type->name);
        self::assertSame('A blog post', $type->description);
        self::assertSame(['id', 'title'], $type->fieldNames());
        self::assertSame(['Node'], $type->interfaces);
        self::assertNotNull($type->field('title'));
        self::assertNull($type->field('missing'));
    }
}
