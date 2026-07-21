<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Filters;

use OpenMeta\Wordpress\Runtime\WordPressRuntimeInterface;

/**
 * OpenMeta → WordPress filter bridge.
 */
final class FilterBridge
{
    public const CONFIG = 'openmeta_config';

    public const MENU_CAPABILITY = 'openmeta_menu_capability';

    public const REST_NAMESPACE = 'openmeta_rest_namespace';

    /** @var array<string, list<callable>> */
    private array $listeners = [];

    public function __construct(private readonly WordPressRuntimeInterface $wp)
    {
    }

    public function on(string $filter, callable $callback): void
    {
        $this->listeners[$filter][] = $callback;
    }

    public function register(): void
    {
        foreach ($this->listeners as $filter => $callbacks) {
            foreach ($callbacks as $callback) {
                $this->wp->addFilter($filter, $callback);
            }
        }
    }

    public function apply(string $filter, mixed $value, mixed ...$args): mixed
    {
        return $this->wp->applyFilters($filter, $value, ...$args);
    }

    /** @return list<string> */
    public function filters(): array
    {
        return array_keys($this->listeners);
    }
}
