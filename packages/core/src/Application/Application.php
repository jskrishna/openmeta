<?php

declare(strict_types=1);

namespace OpenMeta\Core\Application;

use OpenMeta\Core\Bootstrap\Bootstrap;
use OpenMeta\Core\Config\ConfigRepository;
use OpenMeta\Core\Container\Container;
use OpenMeta\Core\Contracts\ApplicationInterface;
use OpenMeta\Core\Contracts\ConfigRepositoryInterface;
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Core\Contracts\KernelInterface;
use OpenMeta\Core\Contracts\ServiceProviderInterface;
use OpenMeta\Core\Events\EventDispatcher;
use OpenMeta\Core\Events\FrameworkBooted;
use OpenMeta\Core\Exceptions\OpenMetaException;
use OpenMeta\Core\Kernel\Kernel;

/**
 * Main framework application.
 *
 * Prefer {@see Bootstrap::run()} / {@see Application::boot()} for the bootstrap sequence:
 *
 *   Load Config → Create Container → Register Core Services
 *   → Register Providers → Boot Providers → Application Ready
 */
final class Application implements ApplicationInterface
{
    public const VERSION = '0.1.0-alpha';

    private ?Container $container = null;

    private ?Kernel $kernel = null;

    private ?ConfigRepositoryInterface $config = null;

    private ?EventDispatcherInterface $events = null;

    private bool $booted = false;

    /**
     * Boot via the canonical bootstrap sequence.
     *
     * @param array<string, mixed> $config Runtime overrides merged over default config files
     * @param list<class-string<ServiceProviderInterface>|ServiceProviderInterface> $providers
     */
    public static function boot(array $config = [], array $providers = []): self
    {
        return Bootstrap::run($config, $providers);
    }

    /**
     * Default config directory: packages/core/config (app, database, api).
     */
    public static function defaultConfigPath(): string
    {
        return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config';
    }

    /**
     * Step 1 — Load Config.
     *
     * @param array<string, mixed> $overrides
     */
    public function loadConfig(array $overrides = [], ?string $path = null): void
    {
        $this->assertNotBooted('load config');

        $this->config = ConfigRepository::load(
            $path ?? self::defaultConfigPath(),
            $overrides
        );
    }

    /**
     * Step 2 — Create Container.
     */
    public function createContainer(): void
    {
        $this->assertNotBooted('create container');

        if ($this->config === null) {
            throw new OpenMetaException('Load configuration before creating the container.');
        }

        $this->container = new Container();
    }

    /**
     * Step 3 — Register Core Services (container, config, events, kernel, application).
     *
     * @param list<class-string<ServiceProviderInterface>|ServiceProviderInterface> $providers
     */
    public function registerCoreServices(array $providers = []): void
    {
        $this->assertNotBooted('register core services');

        if ($this->container === null) {
            throw new OpenMetaException('Create the container before registering core services.');
        }

        if ($this->config === null) {
            throw new OpenMetaException('Load configuration before registering core services.');
        }

        $events = new EventDispatcher();
        $this->events = $events;

        $container = $this->container;

        $container->instance(Container::class, $container);
        $container->instance(ContainerInterface::class, $container);
        $container->instance(ConfigRepository::class, $this->config);
        $container->instance(ConfigRepositoryInterface::class, $this->config);
        $container->alias(ConfigRepositoryInterface::class, 'config');
        $container->instance(EventDispatcher::class, $events);
        $container->instance(EventDispatcherInterface::class, $events);
        $container->instance(\OpenMeta\Core\Events\EventDispatcherInterface::class, $events);
        $container->singleton('events', static fn (): EventDispatcherInterface => $events);

        $kernel = new Kernel($container, $providers);
        $container->instance(Kernel::class, $kernel);
        $container->instance(KernelInterface::class, $kernel);
        $container->instance(self::class, $this);
        $container->instance(ApplicationInterface::class, $this);

        $this->kernel = $kernel;
    }

    /**
     * Step 4 — Register Providers.
     */
    public function registerProviders(): void
    {
        $this->assertNotBooted('register providers');
        $this->kernel()->bootstrap();
        $this->kernel()->registerProviders();
    }

    /**
     * Step 5 — Boot Providers.
     */
    public function bootProviders(): void
    {
        $this->assertNotBooted('boot providers');
        $this->kernel()->bootProviders();
    }

    /**
     * Step 6 — Application Ready.
     */
    public function ready(): void
    {
        $this->assertNotBooted('mark application ready');

        $this->kernel()->ready();
        $this->booted = true;
        $this->events()->dispatch(new FrameworkBooted(self::VERSION));
    }

    /**
     * @deprecated Prefer {@see registerCoreServices()}
     *
     * @param list<class-string<ServiceProviderInterface>|ServiceProviderInterface> $providers
     */
    public function startKernel(array $providers = []): void
    {
        $this->registerCoreServices($providers);
    }

    /**
     * @deprecated Prefer {@see Bootstrap::run()} step sequence
     */
    public function runKernel(): void
    {
        $this->assertNotBooted('run kernel');
        $this->kernel()->run();
        $this->booted = true;
    }

    public function version(): string
    {
        return self::VERSION;
    }

    public function container(): ContainerInterface
    {
        if ($this->container === null) {
            throw new OpenMetaException('Container has not been created yet.');
        }

        return $this->container;
    }

    public function kernel(): KernelInterface
    {
        if ($this->kernel === null) {
            throw new OpenMetaException('Kernel has not been started yet.');
        }

        return $this->kernel;
    }

    public function config(): ConfigRepositoryInterface
    {
        if ($this->config === null) {
            throw new OpenMetaException('Configuration has not been loaded yet.');
        }

        return $this->config;
    }

    public function events(): EventDispatcherInterface
    {
        if ($this->events === null) {
            throw new OpenMetaException('Event dispatcher has not been created yet.');
        }

        return $this->events;
    }

    public function isBooted(): bool
    {
        return $this->booted && $this->kernel !== null && $this->kernel->isReady();
    }

    public function get(string $id): mixed
    {
        return $this->container()->get($id);
    }

    public function has(string $id): bool
    {
        return $this->container()->has($id);
    }

    private function assertNotBooted(string $action): void
    {
        if ($this->booted) {
            throw new OpenMetaException(sprintf(
                'Cannot %s after the application has booted.',
                $action
            ));
        }
    }
}
