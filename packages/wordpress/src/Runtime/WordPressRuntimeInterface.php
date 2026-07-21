<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Runtime;

/**
 * Testable WordPress surface. Native implementation calls WP APIs when present.
 */
interface WordPressRuntimeInterface
{
    public function isLoaded(): bool;

    public function addAction(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): void;

    public function removeAction(string $hook, callable $callback, int $priority = 10): bool;

    public function doAction(string $hook, mixed ...$args): void;

    public function addFilter(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): void;

    public function removeFilter(string $hook, callable $callback, int $priority = 10): bool;

    public function applyFilters(string $hook, mixed $value, mixed ...$args): mixed;

    /**
     * @param callable(): void $callback
     */
    public function addMenuPage(
        string $pageTitle,
        string $menuTitle,
        string $capability,
        string $menuSlug,
        callable $callback,
        string $iconUrl = '',
        ?int $position = null,
    ): void;

    /**
     * @param callable(): void $callback
     */
    public function addSubmenuPage(
        string $parentSlug,
        string $pageTitle,
        string $menuTitle,
        string $capability,
        string $menuSlug,
        callable $callback,
    ): void;

    public function addCapability(string $role, string $capability): void;

    /**
     * @param array<string, mixed> $args
     */
    public function registerRestRoute(string $namespace, string $route, array $args): bool;

    /**
     * @param array<string, mixed> $args
     */
    public function registerBlockType(string $name, array $args = []): bool;

    public function adminNotice(string $message, string $type = 'error'): void;

    public function getPostMeta(int $objectId, string $key, bool $single = true): mixed;

    public function updatePostMeta(int $objectId, string $key, mixed $value): bool;

    public function deletePostMeta(int $objectId, string $key): bool;

    public function getUserMeta(int $objectId, string $key, bool $single = true): mixed;

    public function updateUserMeta(int $objectId, string $key, mixed $value): bool;

    public function deleteUserMeta(int $objectId, string $key): bool;

    public function getTermMeta(int $objectId, string $key, bool $single = true): mixed;

    public function updateTermMeta(int $objectId, string $key, mixed $value): bool;

    public function deleteTermMeta(int $objectId, string $key): bool;

    public function getCommentMeta(int $objectId, string $key, bool $single = true): mixed;

    public function updateCommentMeta(int $objectId, string $key, mixed $value): bool;

    public function deleteCommentMeta(int $objectId, string $key): bool;

    public function getOption(string $key, mixed $default = null): mixed;

    public function updateOption(string $key, mixed $value): bool;

    /**
     * @param array<string, mixed> $args
     */
    public function registerPostType(string $postType, array $args = []): bool;

    /**
     * @param array<string> $objectType
     * @param array<string, mixed> $args
     */
    public function registerTaxonomy(string $taxonomy, array $objectType, array $args = []): bool;

    /**
     * @param list<string> $deps
     */
    public function registerScript(
        string $handle,
        string $src,
        array $deps = [],
        string|false $version = false,
        bool $inFooter = true,
    ): bool;

    /**
     * @param list<string> $deps
     */
    public function registerStyle(
        string $handle,
        string $src,
        array $deps = [],
        string|false $version = false,
    ): bool;

    public function enqueueScript(string $handle): void;

    public function enqueueStyle(string $handle): void;

    public function loadTextDomain(string $domain, string $path): bool;

    public function getCurrentUserId(): int;

    public function getAdminPageSlug(): ?string;
}
