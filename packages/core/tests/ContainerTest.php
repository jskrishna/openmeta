<?php

declare(strict_types=1);

namespace OpenMeta\Core\Tests;

use OpenMeta\Core\Container\Container;
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Exceptions\BindingResolutionException;

final class ContainerTest extends CoreTestCase
{
    private Container $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = new Container();
    }

    public function test_implements_container_interface(): void
    {
        self::assertInstanceOf(ContainerInterface::class, $this->container);
    }

    public function test_bind_resolves_new_instances(): void
    {
        $this->container->bind('counter', static fn (): object => new \stdClass());

        $a = $this->container->resolve('counter');
        $b = $this->container->resolve('counter');

        self::assertNotSame($a, $b);
    }

    public function test_singleton_shares_one_instance(): void
    {
        $this->container->singleton('shared', static fn (): object => new \stdClass());

        self::assertSame(
            $this->container->resolve('shared'),
            $this->container->get('shared')
        );
    }

    public function test_instance_registers_existing_object(): void
    {
        $object = new \stdClass();
        $this->container->instance('obj', $object);

        self::assertSame($object, $this->container->resolve('obj'));
    }

    public function test_alias_resolves_to_abstract(): void
    {
        $this->container->instance(Container::class, $this->container);
        $this->container->alias(Container::class, 'app.container');

        self::assertTrue($this->container->has('app.container'));
        self::assertSame($this->container, $this->container->resolve('app.container'));
    }

    public function test_circular_alias_throws(): void
    {
        $this->container->alias('x', 'y');
        $this->container->alias('y', 'x');

        $this->expectException(BindingResolutionException::class);
        $this->container->resolve('x');
    }

    public function test_missing_binding_throws(): void
    {
        $this->expectException(BindingResolutionException::class);
        $this->container->resolve('missing');
    }
}
