<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Tests\Unit;

use OpenMeta\Sdk\Tests\SdkTestCase;
use OpenMeta\Sdk\Versioning\VersionComparator;

final class VersionConstraintTest extends SdkTestCase
{
    private VersionComparator $comparator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->comparator = new VersionComparator();
    }

    public function test_wildcard_matches_anything(): void
    {
        self::assertTrue($this->comparator->satisfies('9.9.9', '*'));
        self::assertTrue($this->comparator->satisfies('0.0.1', ''));
    }

    public function test_exact_match(): void
    {
        self::assertTrue($this->comparator->satisfies('1.2.3', '1.2.3'));
        self::assertFalse($this->comparator->satisfies('1.2.4', '1.2.3'));
    }

    public function test_caret_range(): void
    {
        self::assertTrue($this->comparator->satisfies('1.2.3', '^1.2.3'));
        self::assertTrue($this->comparator->satisfies('1.9.0', '^1.2.3'));
        self::assertFalse($this->comparator->satisfies('2.0.0', '^1.2.3'));
        self::assertFalse($this->comparator->satisfies('1.2.2', '^1.2.3'));
    }

    public function test_caret_range_below_one(): void
    {
        self::assertTrue($this->comparator->satisfies('0.3.5', '^0.3.0'));
        self::assertFalse($this->comparator->satisfies('0.4.0', '^0.3.0'));
    }

    public function test_tilde_range(): void
    {
        self::assertTrue($this->comparator->satisfies('1.2.9', '~1.2.3'));
        self::assertFalse($this->comparator->satisfies('1.3.0', '~1.2.3'));
        self::assertTrue($this->comparator->satisfies('1.2.0', '~1.2'));
        self::assertFalse($this->comparator->satisfies('1.3.0', '~1.2'));
    }

    public function test_and_range(): void
    {
        self::assertTrue($this->comparator->satisfies('1.5.0', '>=1.0.0 <2.0.0'));
        self::assertFalse($this->comparator->satisfies('2.0.0', '>=1.0.0 <2.0.0'));
        self::assertFalse($this->comparator->satisfies('0.9.0', '>=1.0.0 <2.0.0'));
    }

    public function test_or_range(): void
    {
        self::assertTrue($this->comparator->satisfies('1.0.0', '1.0.0 || 2.0.0'));
        self::assertTrue($this->comparator->satisfies('2.0.0', '1.0.0 || 2.0.0'));
        self::assertFalse($this->comparator->satisfies('3.0.0', '1.0.0 || 2.0.0'));
    }
}
