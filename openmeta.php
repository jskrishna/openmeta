<?php

declare(strict_types=1);

/**
 * Plugin Name:       OpenMeta
 * Plugin URI:        https://github.com/jskrishna/openmeta
 * Description:       Modern open-source content modeling framework for WordPress.
 * Version:           0.8.0-alpha
 * Requires at least: 6.4
 * Requires PHP:      8.3
 * Author:            OpenMeta Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       openmeta
 *
 * @package OpenMeta
 */

if (! defined('ABSPATH')) {
    exit;
}

define('OPENMETA_VERSION', '0.8.0-alpha');
define('OPENMETA_PLUGIN_FILE', __FILE__);
define('OPENMETA_PATH', plugin_dir_path(__FILE__));
define('OPENMETA_URL', plugin_dir_url(__FILE__));

$openmetaAutoload = OPENMETA_PATH . 'vendor/autoload.php';

if (! is_readable($openmetaAutoload)) {
    add_action('admin_notices', static function (): void {
        echo '<div class="notice notice-error"><p>'
            . esc_html('OpenMeta: Composer autoloader missing. Run composer install.')
            . '</p></div>';
    });

    return;
}

require_once $openmetaAutoload;

use OpenMeta\Wordpress\Plugin\Plugin;
use OpenMeta\Wordpress\Plugin\Requirements;
use OpenMeta\Wordpress\Runtime\NativeWordPressRuntime;

$openmetaRuntime = new NativeWordPressRuntime();
$openmetaPlugin = new Plugin($openmetaRuntime, new Requirements(), OPENMETA_PLUGIN_FILE);

register_activation_hook(OPENMETA_PLUGIN_FILE, static function () use ($openmetaPlugin): void {
    $openmetaPlugin->activate();
});

register_deactivation_hook(OPENMETA_PLUGIN_FILE, static function () use ($openmetaPlugin): void {
    $openmetaPlugin->deactivate();
});

add_action('plugins_loaded', static function () use ($openmetaPlugin): void {
    $openmetaPlugin->boot([
        'app' => [
            'env' => defined('WP_DEBUG') && WP_DEBUG ? 'local' : 'production',
        ],
        'database' => [
            'default' => 'memory',
            'connections' => [
                'memory' => ['driver' => 'memory', 'prefix' => 'om_'],
            ],
        ],
    ]);
}, 5);
