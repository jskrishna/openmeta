<?php

declare(strict_types=1);

/**
 * Kernel lifecycle smoke: Bootstrap → Initialize → Ready.
 *
 * Run: php packages/core/tests/Unit/kernel.php
 */

$root = dirname(__DIR__, 4);
require $root . '/vendor/autoload.php';

use OpenMeta\Core\Container\Container;
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Kernel\Kernel;
use OpenMeta\Core\Kernel\KernelPhase;
use OpenMeta\Core\Providers\ServiceProvider;

$log = [];

$provider = new class ($log) extends ServiceProvider {
    /** @param list<string> $log */
    public function __construct(private array &$log)
    {
    }

    public function register(ContainerInterface $container): void
    {
        $this->log[] = 'register';
    }

    public function boot(ContainerInterface $container): void
    {
        $this->log[] = 'boot';
    }
};

$kernel = new Kernel(new Container(), [$provider]);

assert($kernel->phase() === KernelPhase::Pending, 'Expected Pending');

$kernel->bootstrap();
assert($kernel->phase() === KernelPhase::Bootstrap, 'Expected Bootstrap');
assert($log === [], 'Providers must not run during Bootstrap');

$kernel->initialize();
assert($kernel->phase() === KernelPhase::Initialize, 'Expected Initialize');
assert($log === ['register', 'boot'], 'Initialize runs Register → Boot');

$kernel->ready();
assert($kernel->phase() === KernelPhase::Ready, 'Expected Ready');
assert($kernel->isReady() === true, 'isReady');
assert($kernel->isBooted() === true, 'isBooted alias');

// Idempotent full run
$kernel2 = new Kernel(new Container(), [$provider]);
$kernel2->run();
assert($kernel2->phase() === KernelPhase::Ready, 'run() ends Ready');

try {
    $kernel2->addProvider($provider);
    assert(false, 'Cannot add providers after Ready');
} catch (OpenMeta\Core\Exceptions\OpenMetaException) {
    // expected
}

fwrite(STDOUT, "OK Kernel — Bootstrap → Initialize → Ready\n");
exit(0);
