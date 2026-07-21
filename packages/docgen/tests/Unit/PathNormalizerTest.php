<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Tests\Unit;

use OpenMeta\Docgen\Support\PathNormalizer;
use PHPUnit\Framework\TestCase;

final class PathNormalizerTest extends TestCase
{
    public function test_normalizes_dot_segments(): void
    {
        self::assertSame('docs/api/x.md', PathNormalizer::normalize('docs/./api/../api/x.md'));
    }

    public function test_resolves_relative_to_base(): void
    {
        self::assertSame('docs/a.md', PathNormalizer::resolve('docs/guides', '../a.md'));
        self::assertSame('packages/x/SPEC.md', PathNormalizer::resolve('docs/roadmap', '../../packages/x/SPEC.md'));
    }

    public function test_directory_of(): void
    {
        self::assertSame('docs/guides', PathNormalizer::directoryOf('docs/guides/intro.md'));
        self::assertSame('', PathNormalizer::directoryOf('README.md'));
    }
}
