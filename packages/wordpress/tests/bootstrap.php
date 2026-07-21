<?php

declare(strict_types=1);

require dirname(__DIR__, 3) . '/vendor/autoload.php';

require __DIR__ . '/WordpressTestCase.php';

/**
 * Supplemental PSR-4 autoload for packages not yet wired in root composer autoload.
 *
 * @var array<string, string> $prefixes
 */
$prefixes = [
    'OpenMeta\\Admin\\' => dirname(__DIR__, 3) . '/packages/admin/src/',
    'OpenMeta\\Api\\' => dirname(__DIR__, 3) . '/packages/api/src/',
    'OpenMeta\\Builder\\' => dirname(__DIR__, 3) . '/packages/builder/src/',
    'OpenMeta\\Ui\\' => dirname(__DIR__, 3) . '/packages/ui/src/',
    'OpenMeta\\Wordpress\\' => dirname(__DIR__) . '/src/',
    'OpenMeta\\Wordpress\\Tests\\' => __DIR__ . '/',
];

spl_autoload_register(static function (string $class) use ($prefixes): void {
    foreach ($prefixes as $prefix => $baseDir) {
        if (! str_starts_with($class, $prefix)) {
            continue;
        }

        $relative = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', '/', $relative) . '.php';

        if (is_readable($file)) {
            require $file;
        }
    }
});
