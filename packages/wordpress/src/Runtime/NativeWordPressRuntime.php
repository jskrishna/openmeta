<?php

declare(strict_types=1);

namespace OpenMeta\Wordpress\Runtime;

/**
 * Calls real WordPress APIs when available; no-ops / fail-soft otherwise.
 */
final class NativeWordPressRuntime implements WordPressRuntimeInterface
{
    public function isLoaded(): bool
    {
        return defined('ABSPATH') && function_exists('add_action');
    }

    public function addAction(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        if (function_exists('add_action')) {
            add_action($hook, $callback, $priority, $acceptedArgs);
        }
    }

    public function removeAction(string $hook, callable $callback, int $priority = 10): bool
    {
        if (! function_exists('remove_action')) {
            return false;
        }

        return remove_action($hook, $callback, $priority);
    }

    public function doAction(string $hook, mixed ...$args): void
    {
        if (function_exists('do_action')) {
            do_action($hook, ...$args);
        }
    }

    public function addFilter(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        if (function_exists('add_filter')) {
            add_filter($hook, $callback, $priority, $acceptedArgs);
        }
    }

    public function removeFilter(string $hook, callable $callback, int $priority = 10): bool
    {
        if (! function_exists('remove_filter')) {
            return false;
        }

        return remove_filter($hook, $callback, $priority);
    }

    public function applyFilters(string $hook, mixed $value, mixed ...$args): mixed
    {
        if (function_exists('apply_filters')) {
            return apply_filters($hook, $value, ...$args);
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
        if (function_exists('add_menu_page')) {
            add_menu_page($pageTitle, $menuTitle, $capability, $menuSlug, $callback, $iconUrl, $position);
        }
    }

    public function addSubmenuPage(
        string $parentSlug,
        string $pageTitle,
        string $menuTitle,
        string $capability,
        string $menuSlug,
        callable $callback,
    ): void {
        if (function_exists('add_submenu_page')) {
            add_submenu_page($parentSlug, $pageTitle, $menuTitle, $capability, $menuSlug, $callback);
        }
    }

    public function addCapability(string $role, string $capability): void
    {
        if (! function_exists('get_role')) {
            return;
        }

        $wpRole = get_role($role);
        if ($wpRole !== null && method_exists($wpRole, 'add_cap')) {
            $wpRole->add_cap($capability);
        }
    }

    public function registerRestRoute(string $namespace, string $route, array $args): bool
    {
        if (! function_exists('register_rest_route')) {
            return false;
        }

        return (bool) register_rest_route($namespace, $route, $args);
    }

    public function registerBlockType(string $name, array $args = []): bool
    {
        if (! function_exists('register_block_type')) {
            return false;
        }

        $result = register_block_type($name, $args);

        return $result !== false && $result !== null;
    }

    public function adminNotice(string $message, string $type = 'error'): void
    {
        if (! function_exists('add_action')) {
            return;
        }

        add_action('admin_notices', static function () use ($message, $type): void {
            $safeType = preg_replace('/[^a-z]/', '', $type) ?? 'error';
            $class = 'notice notice-' . $safeType;
            $text = function_exists('esc_html')
                ? (string) esc_html($message)
                : htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $attr = function_exists('esc_attr')
                ? (string) esc_attr($class)
                : htmlspecialchars($class, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            echo '<div class="' . $attr . '"><p>' . $text . '</p></div>';
        });
    }

    public function getPostMeta(int $objectId, string $key, bool $single = true): mixed
    {
        if (! function_exists('get_post_meta')) {
            return $single ? '' : [];
        }

        return get_post_meta($objectId, $key, $single);
    }

    public function updatePostMeta(int $objectId, string $key, mixed $value): bool
    {
        if (! function_exists('update_post_meta')) {
            return false;
        }

        return (bool) update_post_meta($objectId, $key, $value);
    }

    public function deletePostMeta(int $objectId, string $key): bool
    {
        if (! function_exists('delete_post_meta')) {
            return false;
        }

        return delete_post_meta($objectId, $key);
    }

    public function getUserMeta(int $objectId, string $key, bool $single = true): mixed
    {
        if (! function_exists('get_user_meta')) {
            return $single ? '' : [];
        }

        return get_user_meta($objectId, $key, $single);
    }

    public function updateUserMeta(int $objectId, string $key, mixed $value): bool
    {
        if (! function_exists('update_user_meta')) {
            return false;
        }

        return (bool) update_user_meta($objectId, $key, $value);
    }

    public function deleteUserMeta(int $objectId, string $key): bool
    {
        if (! function_exists('delete_user_meta')) {
            return false;
        }

        return delete_user_meta($objectId, $key);
    }

    public function getTermMeta(int $objectId, string $key, bool $single = true): mixed
    {
        if (! function_exists('get_term_meta')) {
            return $single ? '' : [];
        }

        return get_term_meta($objectId, $key, $single);
    }

    public function updateTermMeta(int $objectId, string $key, mixed $value): bool
    {
        if (! function_exists('update_term_meta')) {
            return false;
        }

        return (bool) update_term_meta($objectId, $key, $value);
    }

    public function deleteTermMeta(int $objectId, string $key): bool
    {
        if (! function_exists('delete_term_meta')) {
            return false;
        }

        return delete_term_meta($objectId, $key);
    }

    public function getCommentMeta(int $objectId, string $key, bool $single = true): mixed
    {
        if (! function_exists('get_comment_meta')) {
            return $single ? '' : [];
        }

        return get_comment_meta($objectId, $key, $single);
    }

    public function updateCommentMeta(int $objectId, string $key, mixed $value): bool
    {
        if (! function_exists('update_comment_meta')) {
            return false;
        }

        return (bool) update_comment_meta($objectId, $key, $value);
    }

    public function deleteCommentMeta(int $objectId, string $key): bool
    {
        if (! function_exists('delete_comment_meta')) {
            return false;
        }

        return delete_comment_meta($objectId, $key);
    }

    public function getOption(string $key, mixed $default = null): mixed
    {
        if (! function_exists('get_option')) {
            return $default;
        }

        return get_option($key, $default);
    }

    public function updateOption(string $key, mixed $value): bool
    {
        if (! function_exists('update_option')) {
            return false;
        }

        return update_option($key, $value);
    }

    public function registerPostType(string $postType, array $args = []): bool
    {
        if (! function_exists('register_post_type')) {
            return false;
        }

        $result = register_post_type($postType, $args);

        return $result !== null && $result !== false;
    }

    public function registerTaxonomy(string $taxonomy, array $objectType, array $args = []): bool
    {
        if (! function_exists('register_taxonomy')) {
            return false;
        }

        $result = register_taxonomy($taxonomy, $objectType, $args);

        return $result !== null && $result !== false;
    }

    public function registerScript(
        string $handle,
        string $src,
        array $deps = [],
        string|false $version = false,
        bool $inFooter = true,
    ): bool {
        if (! function_exists('wp_register_script')) {
            return false;
        }

        return (bool) wp_register_script($handle, $src, $deps, $version, $inFooter);
    }

    public function registerStyle(
        string $handle,
        string $src,
        array $deps = [],
        string|false $version = false,
    ): bool {
        if (! function_exists('wp_register_style')) {
            return false;
        }

        return (bool) wp_register_style($handle, $src, $deps, $version);
    }

    public function enqueueScript(string $handle): void
    {
        if (function_exists('wp_enqueue_script')) {
            wp_enqueue_script($handle);
        }
    }

    public function enqueueStyle(string $handle): void
    {
        if (function_exists('wp_enqueue_style')) {
            wp_enqueue_style($handle);
        }
    }

    public function loadTextDomain(string $domain, string $path): bool
    {
        if (! function_exists('load_plugin_textdomain')) {
            return false;
        }

        return load_plugin_textdomain($domain, false, $path);
    }

    public function getCurrentUserId(): int
    {
        if (! function_exists('get_current_user_id')) {
            return 0;
        }

        return (int) get_current_user_id();
    }

    public function getAdminPageSlug(): ?string
    {
        if (isset($_GET['page']) && is_string($_GET['page']) && $_GET['page'] !== '') {
            return $_GET['page'];
        }

        return null;
    }
}
