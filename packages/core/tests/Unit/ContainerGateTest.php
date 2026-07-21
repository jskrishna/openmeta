<?php

declare(strict_types=1);

namespace OpenMeta\Core\Tests\Unit;

use OpenMeta\Core\Container\Container;
use OpenMeta\Core\Tests\CoreTestCase;

final class ContainerGateTest extends CoreTestCase
{
    public function test_bind_and_resolve(): void
    {
        $c = new Container();
        $c->bind('x', static fn (): string => 'ok');
        $this->assertSame('ok', $c->get('x'));
    }
}
