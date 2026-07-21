<?php

declare(strict_types=1);

/**
 * Container unit smoke: bind, singleton, resolve, aliases.
 *
 * Run: php packages/core/tests/Unit/container.php
 */

$root = dirname(__DIR__, 4);
require $root . '/vendor/autoload.php';

use OpenMeta\Core\Container\Container;
use OpenMeta\Core\Exceptions\BindingResolutionException;

$container = new Container();

// Bind (transient)
$container->bind('counter', static function (): object {
    return new class {
        public int $n = 0;
    };
});
$a = $container->resolve('counter');
$b = $container->resolve('counter');
assert($a !== $b, 'Transient bind should return new instances');

// Singleton
$container->singleton('shared', static function (): object {
    return new class {
        public string $id = 'shared';
    };
});
assert($container->resolve('shared') === $container->get('shared'), 'Singleton must be shared');

// Alias
$container->instance(Container::class, $container);
$container->alias(Container::class, 'app.container');
assert($container->has('app.container') === true, 'Alias should be has()');
assert($container->resolve('app.container') === $container, 'Alias should resolve to abstract');

// Circular alias
try {
    $container->alias('x', 'y');
    $container->alias('y', 'x');
    $container->resolve('x');
    assert(false, 'Circular alias should throw');
} catch (BindingResolutionException) {
    // expected
}

fwrite(STDOUT, "OK Container — bind / singleton / resolve / aliases\n");
exit(0);
