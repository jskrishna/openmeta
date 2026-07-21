<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Tests\Unit;

use OpenMeta\Generator\Exceptions\StubNotFoundException;
use OpenMeta\Generator\Stubs\StubLoader;
use OpenMeta\Generator\Tests\Fixtures\InMemoryFilesystem;
use PHPUnit\Framework\TestCase;

final class StubLoaderTest extends TestCase
{
    public function test_loads_a_stub(): void
    {
        $fs = new InMemoryFilesystem(['/stubs/field.stub' => 'class {{ class }}']);
        $loader = new StubLoader($fs, ['/stubs']);

        self::assertTrue($loader->has('field'));
        self::assertSame('class {{ class }}', $loader->load('field'));
    }

    public function test_missing_stub_throws(): void
    {
        $loader = new StubLoader(new InMemoryFilesystem(), ['/stubs']);

        $this->expectException(StubNotFoundException::class);
        $loader->load('missing');
    }

    public function test_added_paths_take_priority(): void
    {
        $fs = new InMemoryFilesystem([
            '/base/field.stub' => 'base',
            '/override/field.stub' => 'override',
        ]);
        $loader = new StubLoader($fs, ['/base']);
        $loader->addPath('/override');

        self::assertSame('override', $loader->load('field'));
    }
}
