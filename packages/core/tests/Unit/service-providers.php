<?php

declare(strict_types=1);

/**
 * Service provider lifecycle smoke: Register → Boot order.
 *
 * Run: php packages/core/tests/Unit/service-providers.php
 */

$root = dirname(__DIR__, 4);
require $root . '/vendor/autoload.php';

use OpenMeta\Core\Container\Container;
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Kernel\Kernel;
use OpenMeta\Core\Providers\ServiceProvider;

$log = [];

$providerA = new class ($log) extends ServiceProvider {
    /** @param list<string> $log */
    public function __construct(private array &$log)
    {
    }

    public function register(ContainerInterface $container): void
    {
        $this->log[] = 'A:register';
        $container->bind('from.a', static fn (): string => 'a');
    }

    public function boot(ContainerInterface $container): void
    {
        $this->log[] = 'A:boot';
        // Boot may resolve bindings from other providers' register phase.
        assert($container->has('from.b') === true, 'B must have registered before A boots');
    }
};

$providerB = new class ($log) extends ServiceProvider {
    /** @param list<string> $log */
    public function __construct(private array &$log)
    {
    }

    public function register(ContainerInterface $container): void
    {
        $this->log[] = 'B:register';
        $container->bind('from.b', static fn (): string => 'b');
    }

    public function boot(ContainerInterface $container): void
    {
        $this->log[] = 'B:boot';
        assert($container->get('from.a') === 'a', 'A binding available in B boot');
    }
};

$container = new Container();
$kernel = new Kernel($container, [$providerA, $providerB]);
$kernel->boot();

assert($kernel->isBooted() === true, 'Kernel should be booted');
assert($log === ['A:register', 'B:register', 'A:boot', 'B:boot'], 'Expected Register↓Boot order, got: ' . implode(', ', $log));
assert(count($kernel->providers()) === 2, 'Provider list mismatch');

try {
    $kernel->addProvider($providerA);
    assert(false, 'Adding providers after boot must fail');
} catch (OpenMeta\Core\Exceptions\OpenMetaException) {
    // expected
}

fwrite(STDOUT, "OK Service Providers — Register → Boot\n");
exit(0);
