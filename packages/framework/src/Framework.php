<?php

declare(strict_types=1);

namespace OpenMeta\Framework;

use OpenMeta\Admin\AdminServiceProvider;
use OpenMeta\Api\ApiServiceProvider;
use OpenMeta\Builder\BuilderServiceProvider;
use OpenMeta\Cli\CliServiceProvider;
use OpenMeta\Core\Application\Application;
use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Core\Contracts\ServiceProviderInterface;
use OpenMeta\Database\DatabaseServiceProvider;
use OpenMeta\Docgen\DocgenServiceProvider;
use OpenMeta\Extensions\ExtensionsServiceProvider;
use OpenMeta\Fields\FieldsServiceProvider;
use OpenMeta\Generator\GeneratorServiceProvider;
use OpenMeta\GraphQL\GraphQLServiceProvider;
use OpenMeta\Rest\RestServiceProvider;
use OpenMeta\Security\SecurityServiceProvider;
use OpenMeta\Support\SupportServiceProvider;
use OpenMeta\Ui\UiServiceProvider;
use OpenMeta\Validation\ValidationServiceProvider;
use OpenMeta\Wordpress\Providers\WordpressServiceProvider;

/**
 * Batteries-included entry point for the whole OpenMeta framework.
 *
 * Aggregates every stable package's service provider in dependency order and
 * boots them on the Core container with one call:
 *
 *   $app = Framework::boot();
 *
 * Advanced users may still install and wire individual packages
 * (openmeta/fields, openmeta/database, …) instead of this meta package.
 *
 * Every bundled provider guards host-specific behaviour, so this boots cleanly
 * headless (CLI/tests) as well as inside WordPress.
 */
final class Framework
{
    /**
     * The framework's beta release line (tracks the latest package train).
     */
    public const VERSION = '0.13.0-beta';

    /**
     * All aggregated service providers, in dependency order. Core is bootstrapped
     * by {@see Bootstrap::run()} itself and is therefore not listed here.
     *
     * @return list<class-string<ServiceProviderInterface>>
     */
    public static function providers(): array
    {
        return [
            SupportServiceProvider::class,
            ValidationServiceProvider::class,
            SecurityServiceProvider::class,
            DatabaseServiceProvider::class,
            FieldsServiceProvider::class,
            RestServiceProvider::class,
            ApiServiceProvider::class,
            UiServiceProvider::class,
            AdminServiceProvider::class,
            BuilderServiceProvider::class,
            WordpressServiceProvider::class,
            ExtensionsServiceProvider::class,
            GraphQLServiceProvider::class,
            CliServiceProvider::class,
            GeneratorServiceProvider::class,
            DocgenServiceProvider::class,
        ];
    }

    /**
     * Boot the full framework on a fresh Core application.
     *
     * @param array<string, mixed> $config Runtime config merged over framework defaults
     * @param list<class-string<ServiceProviderInterface>|ServiceProviderInterface> $providers
     *        Extra providers appended after the aggregate set
     */
    public static function boot(array $config = [], array $providers = []): Application
    {
        $merged = array_replace_recursive(self::defaultConfig(), $config);

        return Bootstrap::run($merged, [...self::providers(), ...$providers]);
    }

    /**
     * Sensible defaults so the aggregate stack boots headless out of the box.
     *
     * @return array<string, mixed>
     */
    private static function defaultConfig(): array
    {
        return [
            'app' => ['key' => 'openmeta-framework'],
            'database' => [
                'default' => 'memory',
                'connections' => [
                    'memory' => ['driver' => 'memory', 'prefix' => 'om_'],
                ],
            ],
        ];
    }
}
