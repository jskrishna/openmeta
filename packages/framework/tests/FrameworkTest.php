<?php

declare(strict_types=1);

namespace OpenMeta\Framework\Tests;

use OpenMeta\Cli\Contracts\ConsoleApplicationInterface;
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Contracts\ServiceProviderInterface;
use OpenMeta\Extensions\Contracts\ExtensionManagerInterface;
use OpenMeta\Fields\Registry\FieldRegistry;
use OpenMeta\Framework\Framework;
use OpenMeta\GraphQL\Contracts\GraphQLManagerInterface;
use OpenMeta\Security\Contracts\GateInterface;
use PHPUnit\Framework\TestCase;

/**
 * Verifies the meta package boots the whole stack and wires every layer.
 */
final class FrameworkTest extends TestCase
{
    public function test_every_provider_is_a_service_provider(): void
    {
        $providers = Framework::providers();

        self::assertNotEmpty($providers);

        foreach ($providers as $provider) {
            self::assertTrue(class_exists($provider), "provider [{$provider}] does not exist");
            self::assertContains(
                ServiceProviderInterface::class,
                class_implements($provider) ?: [],
                "provider [{$provider}] must implement ServiceProviderInterface",
            );
        }
    }

    public function test_boot_returns_a_booted_application(): void
    {
        $app = Framework::boot();

        self::assertTrue($app->isBooted());
    }

    public function test_boot_wires_services_across_layers(): void
    {
        $app = Framework::boot();

        self::assertInstanceOf(GateInterface::class, $app->get(GateInterface::class));
        self::assertInstanceOf(FieldRegistry::class, $app->get(FieldRegistry::class));
        self::assertInstanceOf(GraphQLManagerInterface::class, $app->get(GraphQLManagerInterface::class));
        self::assertInstanceOf(ConsoleApplicationInterface::class, $app->get(ConsoleApplicationInterface::class));
        self::assertInstanceOf(ExtensionManagerInterface::class, $app->get(ExtensionManagerInterface::class));
    }

    public function test_boot_appends_extra_providers(): void
    {
        $provider = new class implements ServiceProviderInterface {
            public function register(ContainerInterface $container): void
            {
                $container->instance('framework.test.marker', (object) ['ok' => true]);
            }

            public function boot(ContainerInterface $container): void
            {
            }
        };

        $app = Framework::boot([], [$provider]);

        self::assertTrue($app->has('framework.test.marker'));
    }

    public function test_version_is_exposed(): void
    {
        self::assertMatchesRegularExpression('/^\d+\.\d+\.\d+/', Framework::VERSION);
    }
}
