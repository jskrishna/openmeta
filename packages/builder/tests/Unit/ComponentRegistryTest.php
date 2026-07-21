<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Tests\Unit;

use OpenMeta\Builder\Registry\ComponentDescriptor;
use OpenMeta\Builder\Registry\ComponentRegistry;
use OpenMeta\Builder\Tests\BuilderTestCase;

final class ComponentRegistryTest extends BuilderTestCase
{
    public function test_register_category_and_lazy_resolve(): void
    {
        $registry = new ComponentRegistry();
        $registry->register(new ComponentDescriptor(
            'hero',
            'Hero',
            'marketing',
            '1.0.0',
            ['landing'],
            static fn (): array => ['headline' => 'Hello'],
        ));

        $this->assertTrue($registry->has('hero'));
        $this->assertSame(['marketing'], $registry->categories());
        $this->assertSame(['headline' => 'Hello'], $registry->get('hero')->resolve());
    }
}
