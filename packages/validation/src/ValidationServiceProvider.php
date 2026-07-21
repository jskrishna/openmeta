<?php

declare(strict_types=1);

namespace OpenMeta\Validation;

use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Validation\Messages\MessageBag;
use OpenMeta\Validation\Registry\RuleEngine;
use OpenMeta\Validation\Registry\RuleRegistry;

/**
 * Binds Rule Engine, registry, and message bag. Built-in rules register on register().
 */
final class ValidationServiceProvider extends ServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        $container->singleton(RuleRegistry::class, static function (): RuleRegistry {
            $registry = new RuleRegistry();
            $registry->registerDefaults();

            return $registry;
        });

        $container->singleton(RuleEngine::class, static function (ContainerInterface $c): RuleEngine {
            return new RuleEngine($c->get(RuleRegistry::class));
        });

        $container->singleton(MessageBag::class, static fn (): MessageBag => new MessageBag());

        $container->alias(RuleRegistry::class, 'validation.registry');
        $container->alias(RuleEngine::class, 'validation.engine');
    }

    public function boot(ContainerInterface $container): void
    {
        Validation::useRegistry($container->get(RuleRegistry::class));
    }
}
