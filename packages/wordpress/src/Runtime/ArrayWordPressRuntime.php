<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Runtime;

/**
 * In-memory WP runtime for CI / unit tests (no WordPress required).
 */
final class ArrayWordPressRuntime implements WordPressRuntimeInterface
{
    /** @var array<string, list<array{callback: callable, priority: int}>> */
    public array $actions = [];

    /** @var array<string, list<array{callback: callable, priority: int}>> */
    public array $filters = [];

    /** @var list<array<string, mixed>> */
    public array $menus = [];

    /** @var list<array{role: string, capability: string}> */
    public array $capabilities = [];

    /** @var list<array{namespace: string, route: string, args: array<string, mixed>}> */
    public array $restRoutes = [];

    /** @var list<array{name: string, args: array<string, mixed>}> */
    public array $blocks = [];

    /** @var list<array{message: string, type: string}> */
    public array $notices = [];

    /** @var array<string, array<string, mixed>> */
    public array $postMeta = [];

    /** @var array<string, array<string, mixed>> */
    public array $userMeta = [];

    /** @var array<string, array<string, mixed>> */
    public array $termMeta = [];

    /** @var array<string, array<string, mixed>> */
    public array $commentMeta = [];

    /** @var array<string, mixed> */
    public array $options = [];

    /** @var list<array{post_type: string, args: array<string, mixed>}> */
    public array $postTypes = [];

    /** @var list<array{taxonomy: string, object_type: list<string>, args: array<string, mixed>}> */
    public array $taxonomies = [];

    /** @var list<array{handle: string, src: string, deps: list<string>, version: string|false, in_footer: bool}> */
    public array $scripts = [];

    /** @var list<array{handle: string, src: string, deps: list<string>, version: string|false}> */
    public array $styles = [];

    /** @var list<string> */
    public array $enqueuedScripts = [];

    /** @var list<string> */
    public array $enqueuedStyles = [];

    /** @var list<array{domain: string, path: string}> */
    public array $textDomains = [];

    public int $currentUserId = 0;

    public function isLoaded(): bool
    {
        return true;
    }

    public function addAction(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        $this->actions[$hook][] = ['callback' => $callback, 'priority' => $priority];
    }

    public function removeAction(string $hook, callable $callback, int $priority = 10): bool
    {
        if (! isset($this->actions[$hook])) {
            return false;
        }

        $before = count($this->actions[$hook]);
        $this->actions[$hook] = array_values(array_filter(
            $this->actions[$hook],
            static fn (array $listener): bool => $listener['callback'] !== $callback
                || $listener['priority'] !== $priority
        ));

        return count($this->actions[$hook]) < $before;
    }

    public function doAction(string $hook, mixed ...$args): void
    {
        foreach ($this->actions[$hook] ?? [] as $listener) {
            ($listener['callback'])(...$args);
        }
    }

    public function addFilter(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        $this->filters[$hook][] = ['callback' => $callback, 'priority' => $priority];
    }

    public function removeFilter(string $hook, callable $callback, int $priority = 10): bool
    {
        if (! isset($this->filters[$hook])) {
            return false;
        }

        $before = count($this->filters[$hook]);
        $this->filters[$hook] = array_values(array_filter(
            $this->filters[$hook],
            static fn (array $listener): bool => $listener['callback'] !== $callback
                || $listener['priority'] !== $priority
        ));

        return count($this->filters[$hook]) < $before;
    }

    public function applyFilters(string $hook, mixed $value, mixed ...$args): mixed
    {
        foreach ($this->filters[$hook] ?? [] as $listener) {
            $value = ($listener['callback'])($value, ...$args);
        }

        return $value;
    }

    public function addMenuPage(
        string $pageTitle,
        string $menuTitle,
        string $capability,
        string $menuSlug,
        callable $callback,
        string $iconUrl = '',
        ?int $position = null,
    ): void {
        $this->menus[] = [
            'type' => 'top',
            'page_title' => $pageTitle,
            'menu_title' => $menuTitle,
            'capability' => $capability,
            'menu_slug' => $menuSlug,
            'callback' => $callback,
            'icon' => $iconUrl,
            'position' => $position,
        ];
    }

    public function addSubmenuPage(
        string $parentSlug,
        string $pageTitle,
        string $menuTitle,
        string $capability,
        string $menuSlug,
        callable $callback,
    ): void {
        $this->menus[] = [
            'type' => 'sub',
            'parent' => $parentSlug,
            'page_title' => $pageTitle,
            'menu_title' => $menuTitle,
            'capability' => $capability,
            'menu_slug' => $menuSlug,
            'callback' => $callback,
        ];
    }

    public function addCapability(string $role, string $capability): void
    {
        $this->capabilities[] = ['role' => $role, 'capability' => $capability];
    }

    public function registerRestRoute(string $namespace, string $route, array $args): bool
    {
        $this->restRoutes[] = [
            'namespace' => $namespace,
            'route' => $route,
            'args' => $args,
        ];

        return true;
    }

    public function registerBlockType(string $name, array $args = []): bool
    {
        $this->blocks[] = ['name' => $name, 'args' => $args];

        return true;
    }

    public function adminNotice(string $message, string $type = 'error'): void
    {
        $this->notices[] = ['message' => $message, 'type' => $type];
    }

    public function getPostMeta(int $objectId, string $key, bool $single = true): mixed
    {
        return $this->readMeta($this->postMeta, $this->metaBucket($objectId), $key, $single);
    }

    public function updatePostMeta(int $objectId, string $key, mixed $value): bool
    {
        $id = $this->metaBucket($objectId);
        if (! isset($this->postMeta[$id])) {
            $this->postMeta[$id] = [];
        }
        $this->postMeta[$id][$key] = $value;

        return true;
    }

    public function deletePostMeta(int $objectId, string $key): bool
    {
        $id = $this->metaBucket($objectId);
        if (! isset($this->postMeta[$id][$key])) {
            return false;
        }

        unset($this->postMeta[$id][$key]);

        return true;
    }

    public function getUserMeta(int $objectId, string $key, bool $single = true): mixed
    {
        return $this->readMeta($this->userMeta, $this->metaBucket($objectId), $key, $single);
    }

    public function updateUserMeta(int $objectId, string $key, mixed $value): bool
    {
        $id = $this->metaBucket($objectId);
        if (! isset($this->userMeta[$id])) {
            $this->userMeta[$id] = [];
        }
        $this->userMeta[$id][$key] = $value;

        return true;
    }

    public function deleteUserMeta(int $objectId, string $key): bool
    {
        $id = $this->metaBucket($objectId);
        if (! isset($this->userMeta[$id][$key])) {
            return false;
        }

        unset($this->userMeta[$id][$key]);

        return true;
    }

    public function getTermMeta(int $objectId, string $key, bool $single = true): mixed
    {
        return $this->readMeta($this->termMeta, $this->metaBucket($objectId), $key, $single);
    }

    public function updateTermMeta(int $objectId, string $key, mixed $value): bool
    {
        $id = $this->metaBucket($objectId);
        if (! isset($this->termMeta[$id])) {
            $this->termMeta[$id] = [];
        }
        $this->termMeta[$id][$key] = $value;

        return true;
    }

    public function deleteTermMeta(int $objectId, string $key): bool
    {
        $id = $this->metaBucket($objectId);
        if (! isset($this->termMeta[$id][$key])) {
            return false;
        }

        unset($this->termMeta[$id][$key]);

        return true;
    }

    public function getCommentMeta(int $objectId, string $key, bool $single = true): mixed
    {
        return $this->readMeta($this->commentMeta, $this->metaBucket($objectId), $key, $single);
    }

    public function updateCommentMeta(int $objectId, string $key, mixed $value): bool
    {
        $id = $this->metaBucket($objectId);
        if (! isset($this->commentMeta[$id])) {
            $this->commentMeta[$id] = [];
        }
        $this->commentMeta[$id][$key] = $value;

        return true;
    }

    public function deleteCommentMeta(int $objectId, string $key): bool
    {
        $id = $this->metaBucket($objectId);
        if (! isset($this->commentMeta[$id][$key])) {
            return false;
        }

        unset($this->commentMeta[$id][$key]);

        return true;
    }

    public function getOption(string $key, mixed $default = null): mixed
    {
        return $this->options[$key] ?? $default;
    }

    public function updateOption(string $key, mixed $value): bool
    {
        $this->options[$key] = $value;

        return true;
    }

    public function registerPostType(string $postType, array $args = []): bool
    {
        $this->postTypes[] = ['post_type' => $postType, 'args' => $args];

        return true;
    }

    public function registerTaxonomy(string $taxonomy, array $objectType, array $args = []): bool
    {
        $this->taxonomies[] = [
            'taxonomy' => $taxonomy,
            'object_type' => $objectType,
            'args' => $args,
        ];

        return true;
    }

    public function registerScript(
        string $handle,
        string $src,
        array $deps = [],
        string|false $version = false,
        bool $inFooter = true,
    ): bool {
        $this->scripts[] = [
            'handle' => $handle,
            'src' => $src,
            'deps' => $deps,
            'version' => $version,
            'in_footer' => $inFooter,
        ];

        return true;
    }

    public function registerStyle(
        string $handle,
        string $src,
        array $deps = [],
        string|false $version = false,
    ): bool {
        $this->styles[] = [
            'handle' => $handle,
            'src' => $src,
            'deps' => $deps,
            'version' => $version,
        ];

        return true;
    }

    public function enqueueScript(string $handle): void
    {
        $this->enqueuedScripts[] = $handle;
    }

    public function enqueueStyle(string $handle): void
    {
        $this->enqueuedStyles[] = $handle;
    }

    public function loadTextDomain(string $domain, string $path): bool
    {
        $this->textDomains[] = ['domain' => $domain, 'path' => $path];

        return true;
    }

    public function getCurrentUserId(): int
    {
        return $this->currentUserId;
    }

    public function getAdminPageSlug(): ?string
    {
        if (isset($_GET['page']) && is_string($_GET['page']) && $_GET['page'] !== '') {
            return $_GET['page'];
        }

        return null;
    }

    private function metaBucket(int $objectId): string
    {
        return 'object_' . $objectId;
    }

    /**
     * @param array<string, array<string, mixed>> $store
     */
    private function readMeta(array $store, string $objectId, string $key, bool $single): mixed
    {
        $bucket = $store[$objectId][$key] ?? null;

        if ($bucket === null) {
            return $single ? '' : [];
        }

        return $single ? $bucket : [$bucket];
    }
}
