<?php

declare(strict_types=1);

namespace OpenMeta\Extensions\Tests\Fixtures;

use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Providers\ServiceProvider;

/**
 * A service provider that records how many times it was registered and booted.
 *
 * Uses static counters because the loader instantiates it with `new` and has
 * no reference back to the test.
 */
final class RecordingProvider extends ServiceProvider
{
    public static int $registered = 0;

    public static int $booted = 0;

    public static function reset(): void
    {
        self::$registered = 0;
        self::$booted = 0;
    }

    public function register(ContainerInterface $container): void
    {
        self::$registered++;
    }

    public function boot(ContainerInterface $container): void
    {
        self::$booted++;
    }
}
