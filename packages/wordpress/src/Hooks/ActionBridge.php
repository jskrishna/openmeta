<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Hooks;

use OpenMeta\Wordpress\Runtime\WordPressRuntimeInterface;

/**
 * OpenMeta → WordPress action bridge.
 */
final class ActionBridge
{
    public const READY = 'openmeta_ready';

    public const ADMIN_INIT = 'openmeta_admin_init';

    public const REST_INIT = 'openmeta_rest_init';

    /** @var array<string, list<callable>> */
    private array $listeners = [];

    public function __construct(private readonly WordPressRuntimeInterface $wp)
    {
    }

    public function on(string $action, callable $callback): void
    {
        $this->listeners[$action][] = $callback;
    }

    /**
     * Wire registered listeners onto the WordPress runtime.
     */
    public function register(): void
    {
        foreach ($this->listeners as $action => $callbacks) {
            foreach ($callbacks as $callback) {
                $this->wp->addAction($action, $callback);
            }
        }

        $this->wp->addAction('plugins_loaded', [$this, 'onPluginsLoaded'], 20);
    }

    public function onPluginsLoaded(): void
    {
        $this->wp->doAction(self::READY);
    }

    /** @return list<string> */
    public function actions(): array
    {
        return array_keys($this->listeners);
    }
}
