<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Tests\Unit;

use OpenMeta\Extensions\Exceptions\ExtensionNotFoundException;
use OpenMeta\Extensions\Lifecycle\ExtensionState;
use OpenMeta\Extensions\Registry\Extension;
use OpenMeta\Extensions\Registry\ExtensionRegistry;
use OpenMeta\Extensions\Tests\ExtensionsTestCase;

final class ExtensionRegistryTest extends ExtensionsTestCase
{
    public function test_add_get_and_has(): void
    {
        $registry = new ExtensionRegistry();
        $registry->add(new Extension($this->manifest('acme/a')));

        self::assertTrue($registry->has('acme/a'));
        self::assertSame('acme/a', $registry->get('acme/a')->id());
        self::assertNull($registry->find('acme/missing'));
    }

    public function test_get_missing_throws(): void
    {
        $this->expectException(ExtensionNotFoundException::class);

        (new ExtensionRegistry())->get('acme/missing');
    }

    public function test_filters_by_state(): void
    {
        $registry = new ExtensionRegistry();
        $registry->add(new Extension($this->manifest('acme/a'), ExtensionState::Active));
        $registry->add(new Extension($this->manifest('acme/b'), ExtensionState::Installed));

        self::assertCount(1, $registry->byState(ExtensionState::Active));
        self::assertSame('acme/a', $registry->byState(ExtensionState::Active)[0]->id());
        self::assertCount(2, $registry->all());
    }

    public function test_remove(): void
    {
        $registry = new ExtensionRegistry();
        $registry->add(new Extension($this->manifest('acme/a')));
        $registry->remove('acme/a');

        self::assertFalse($registry->has('acme/a'));
    }
}
