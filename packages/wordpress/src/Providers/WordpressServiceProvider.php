<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Providers;

use OpenMeta\Admin\Admin;
use OpenMeta\Api\Rest\RestKernel as ApiRestKernel;
use OpenMeta\Api\Rest\Routes\Router as ApiRouter;
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Fields\Contracts\FieldStorageInterface;
use OpenMeta\Fields\Storage\StorageRegistry;
use OpenMeta\Rest\RestKernel as FrameworkRestKernel;
use OpenMeta\Rest\Router\Router as FrameworkRouter;
use OpenMeta\Security\Contracts\CapabilityCheckerInterface;
use OpenMeta\Security\Contracts\NonceHandlerInterface;
use OpenMeta\Security\Permissions\PermissionMap;
use OpenMeta\Wordpress\Adapters\AdapterRegistry;
use OpenMeta\Wordpress\Admin\AdminPages;
use OpenMeta\Wordpress\Assets\AssetManager;
use OpenMeta\Wordpress\Bootstrap\EnvironmentChecker;
use OpenMeta\Wordpress\Capabilities\CapabilityRegistrar;
use OpenMeta\Wordpress\Capabilities\WordPressCapabilityChecker;
use OpenMeta\Wordpress\Configuration\PluginConfiguration;
use OpenMeta\Wordpress\Contracts\AssetManagerInterface;
use OpenMeta\Wordpress\Contracts\FilterManagerInterface;
use OpenMeta\Wordpress\Contracts\HookManagerInterface;
use OpenMeta\Wordpress\Contracts\LifecycleManagerInterface;
use OpenMeta\Wordpress\Filters\FilterBridge;
use OpenMeta\Wordpress\Filters\FilterManager;
use OpenMeta\Wordpress\Gutenberg\BlockRegistrar;
use OpenMeta\Wordpress\Hooks\ActionBridge;
use OpenMeta\Wordpress\Hooks\HookManager;
use OpenMeta\Wordpress\Lifecycle\LifecycleManager;
use OpenMeta\Wordpress\Lifecycle\UpgradeManager;
use OpenMeta\Wordpress\Localization\LocalizationAdapter;
use OpenMeta\Wordpress\Meta\CommentMetaAdapter;
use OpenMeta\Wordpress\Meta\OptionsAdapter;
use OpenMeta\Wordpress\Meta\PostMetaAdapter;
use OpenMeta\Wordpress\Meta\TermMetaAdapter;
use OpenMeta\Wordpress\Meta\UserMetaAdapter;
use OpenMeta\Wordpress\Meta\WordPressFieldStorage;
use OpenMeta\Wordpress\Nonces\WordPressNonceHandler;
use OpenMeta\Wordpress\Plugin\Plugin;
use OpenMeta\Wordpress\Plugin\Requirements;
use OpenMeta\Wordpress\PostTypes\PostTypeRegistrar;
use OpenMeta\Wordpress\Rest\FrameworkRestBridge;
use OpenMeta\Wordpress\Rest\RestBridge;
use OpenMeta\Wordpress\Runtime\NativeWordPressRuntime;
use OpenMeta\Wordpress\Runtime\WordPressRuntimeInterface;
use OpenMeta\Wordpress\Settings\SettingsAdapter;
use OpenMeta\Wordpress\Support\DependencyChecker;
use OpenMeta\Wordpress\Taxonomies\TaxonomyRegistrar;
use OpenMeta\Wordpress\Users\UserAdapter;

/**
 * Binds WP bridges and registers them on boot.
 */
class WordpressServiceProvider extends ServiceProvider
{
    public function __construct(
        private readonly ?WordPressRuntimeInterface $runtime = null,
        private readonly string $pluginFile = '',
    ) {
    }

    public function register(ContainerInterface $container): void
    {
        $wp = $this->runtime ?? new NativeWordPressRuntime();
        $container->instance(WordPressRuntimeInterface::class, $wp);
        $container->alias(WordPressRuntimeInterface::class, 'wordpress');

        $pluginFile = $this->pluginFile !== '' ? $this->pluginFile : dirname(__DIR__, 4) . '/openmeta.php';
        $configuration = PluginConfiguration::fromPluginFile($pluginFile);
        $container->instance(PluginConfiguration::class, $configuration);

        $container->singleton(Requirements::class, static fn (): Requirements => new Requirements());
        $container->singleton(EnvironmentChecker::class, static fn (): EnvironmentChecker => new EnvironmentChecker());
        $container->singleton(DependencyChecker::class, static fn (): DependencyChecker => new DependencyChecker());

        $container->singleton(HookManager::class, static function (ContainerInterface $c): HookManager {
            return new HookManager($c->get(WordPressRuntimeInterface::class));
        });
        $container->singleton(
            HookManagerInterface::class,
            static function (ContainerInterface $c): HookManagerInterface {
                return $c->get(HookManager::class);
            }
        );

        $container->singleton(FilterManager::class, static function (ContainerInterface $c): FilterManager {
            return new FilterManager($c->get(WordPressRuntimeInterface::class));
        });
        $container->singleton(
            FilterManagerInterface::class,
            static function (ContainerInterface $c): FilterManagerInterface {
                return $c->get(FilterManager::class);
            }
        );

        $container->singleton(ActionBridge::class, static function (ContainerInterface $c): ActionBridge {
            return new ActionBridge($c->get(WordPressRuntimeInterface::class));
        });

        $container->singleton(FilterBridge::class, static function (ContainerInterface $c): FilterBridge {
            return new FilterBridge($c->get(WordPressRuntimeInterface::class));
        });

        $container->singleton(CapabilityRegistrar::class, static function (ContainerInterface $c): CapabilityRegistrar {
            return new CapabilityRegistrar(
                $c->get(WordPressRuntimeInterface::class),
                $c->has(PermissionMap::class) ? $c->get(PermissionMap::class) : new PermissionMap(),
            );
        });

        if (function_exists('current_user_can')) {
            $container->singleton(
                CapabilityCheckerInterface::class,
                static fn (): CapabilityCheckerInterface => new WordPressCapabilityChecker()
            );
        }

        if (function_exists('wp_create_nonce') && function_exists('wp_verify_nonce')) {
            $container->singleton(
                NonceHandlerInterface::class,
                static fn (): NonceHandlerInterface => new WordPressNonceHandler()
            );
        }

        $container->singleton(PostMetaAdapter::class, static function (ContainerInterface $c): PostMetaAdapter {
            return new PostMetaAdapter($c->get(WordPressRuntimeInterface::class));
        });
        $container->singleton(UserMetaAdapter::class, static function (ContainerInterface $c): UserMetaAdapter {
            return new UserMetaAdapter($c->get(WordPressRuntimeInterface::class));
        });
        $container->singleton(TermMetaAdapter::class, static function (ContainerInterface $c): TermMetaAdapter {
            return new TermMetaAdapter($c->get(WordPressRuntimeInterface::class));
        });
        $container->singleton(CommentMetaAdapter::class, static function (ContainerInterface $c): CommentMetaAdapter {
            return new CommentMetaAdapter($c->get(WordPressRuntimeInterface::class));
        });
        $container->singleton(OptionsAdapter::class, static function (ContainerInterface $c): OptionsAdapter {
            return new OptionsAdapter($c->get(WordPressRuntimeInterface::class));
        });

        $container->singleton(
            WordPressFieldStorage::class,
            static function (ContainerInterface $c): WordPressFieldStorage {
                return new WordPressFieldStorage(
                    $c->get(PostMetaAdapter::class),
                    $c->get(UserMetaAdapter::class),
                    $c->get(TermMetaAdapter::class),
                    $c->get(CommentMetaAdapter::class),
                    $c->get(OptionsAdapter::class),
                );
            }
        );

        $container->alias(WordPressFieldStorage::class, FieldStorageInterface::class);

        $container->singleton(PostTypeRegistrar::class, static function (ContainerInterface $c): PostTypeRegistrar {
            return new PostTypeRegistrar($c->get(WordPressRuntimeInterface::class));
        });
        $container->singleton(TaxonomyRegistrar::class, static function (ContainerInterface $c): TaxonomyRegistrar {
            return new TaxonomyRegistrar($c->get(WordPressRuntimeInterface::class));
        });

        $container->singleton(UserAdapter::class, static function (ContainerInterface $c): UserAdapter {
            return new UserAdapter(
                $c->get(WordPressRuntimeInterface::class),
                $c->get(CapabilityCheckerInterface::class),
            );
        });

        $container->singleton(SettingsAdapter::class, static function (ContainerInterface $c): SettingsAdapter {
            return new SettingsAdapter($c->get(WordPressRuntimeInterface::class));
        });

        $container->singleton(AssetManager::class, static function (ContainerInterface $c): AssetManager {
            return new AssetManager(
                $c->get(WordPressRuntimeInterface::class),
                $c->get(PluginConfiguration::class),
            );
        });
        $container->singleton(
            AssetManagerInterface::class,
            static function (ContainerInterface $c): AssetManagerInterface {
                return $c->get(AssetManager::class);
            }
        );

        $container->singleton(LocalizationAdapter::class, static function (ContainerInterface $c): LocalizationAdapter {
            return new LocalizationAdapter(
                $c->get(WordPressRuntimeInterface::class),
                $c->get(PluginConfiguration::class),
            );
        });

        $container->singleton(UpgradeManager::class, static function (ContainerInterface $c): UpgradeManager {
            return new UpgradeManager(
                $c->get(WordPressRuntimeInterface::class),
                $c->get(PluginConfiguration::class),
            );
        });

        $container->singleton(LifecycleManager::class, static function (ContainerInterface $c): LifecycleManager {
            return new LifecycleManager(
                $c->get(WordPressRuntimeInterface::class),
                $c->get(EventDispatcherInterface::class),
                $c->get(UpgradeManager::class),
                $c->get(PluginConfiguration::class),
            );
        });
        $container->singleton(
            LifecycleManagerInterface::class,
            static function (ContainerInterface $c): LifecycleManagerInterface {
                return $c->get(LifecycleManager::class);
            }
        );

        $container->singleton(AdapterRegistry::class, static function (ContainerInterface $c): AdapterRegistry {
            $registry = new AdapterRegistry();
            $registry->register('post_meta', $c->get(PostMetaAdapter::class));
            $registry->register('user_meta', $c->get(UserMetaAdapter::class));
            $registry->register('term_meta', $c->get(TermMetaAdapter::class));
            $registry->register('comment_meta', $c->get(CommentMetaAdapter::class));
            $registry->register('options', $c->get(OptionsAdapter::class));
            $registry->register('user', $c->get(UserAdapter::class));
            $registry->register('settings', $c->get(SettingsAdapter::class));

            return $registry;
        });

        $container->singleton(BlockRegistrar::class, static function (ContainerInterface $c): BlockRegistrar {
            return new BlockRegistrar($c->get(WordPressRuntimeInterface::class));
        });

        $container->singleton(AdminPages::class, static function (ContainerInterface $c): AdminPages {
            return new AdminPages(
                $c->get(WordPressRuntimeInterface::class),
                $c->get(Admin::class),
                $c->get(FilterBridge::class),
                $c->has(PermissionMap::class) ? $c->get(PermissionMap::class) : new PermissionMap(),
            );
        });

        $container->singleton(RestBridge::class, static function (ContainerInterface $c): RestBridge {
            return new RestBridge(
                $c->get(WordPressRuntimeInterface::class),
                $c->get(ApiRouter::class),
                $c->get(ApiRestKernel::class),
                $c->get(FilterBridge::class),
            );
        });

        if ($container->has(FrameworkRestKernel::class)) {
            $container->singleton(
                FrameworkRestBridge::class,
                static function (ContainerInterface $c): FrameworkRestBridge {
                    return new FrameworkRestBridge(
                        $c->get(WordPressRuntimeInterface::class),
                        $c->get(FrameworkRouter::class),
                        $c->get(FrameworkRestKernel::class),
                        $c->get(FilterBridge::class),
                    );
                }
            );
        }

        $container->singleton(Plugin::class, static function (ContainerInterface $c) use ($pluginFile): Plugin {
            return new Plugin(
                $c->get(WordPressRuntimeInterface::class),
                $c->get(Requirements::class),
                $pluginFile,
            );
        });

        $container->alias(Plugin::class, 'plugin');
    }

    public function boot(ContainerInterface $container): void
    {
        /** @var ActionBridge $actions */
        $actions = $container->get(ActionBridge::class);
        /** @var FilterBridge $filters */
        $filters = $container->get(FilterBridge::class);
        /** @var AdminPages $adminPages */
        $adminPages = $container->get(AdminPages::class);
        /** @var BlockRegistrar $blocks */
        $blocks = $container->get(BlockRegistrar::class);
        /** @var RestBridge $rest */
        $rest = $container->get(RestBridge::class);
        /** @var LocalizationAdapter $localization */
        $localization = $container->get(LocalizationAdapter::class);
        /** @var LifecycleManager $lifecycle */
        $lifecycle = $container->get(LifecycleManager::class);
        /** @var WordPressRuntimeInterface $wp */
        $wp = $container->get(WordPressRuntimeInterface::class);

        $actions->register();
        $filters->register();
        $adminPages->register();
        $blocks->register();
        $rest->register();
        $localization->register();
        $lifecycle->register();

        if ($container->has(FrameworkRestBridge::class)) {
            $container->get(FrameworkRestBridge::class)->register();
        }

        if ($container->has(StorageRegistry::class)) {
            $container->get(StorageRegistry::class)->register(
                'wordpress',
                $container->get(WordPressFieldStorage::class),
            );
        }

        $wp->doAction(ActionBridge::READY);
    }
}
