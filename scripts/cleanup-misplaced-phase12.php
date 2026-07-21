<?php

declare(strict_types=1);

$root = dirname(__DIR__) . '/packages';
$packages = ['core', 'support', 'validation', 'security', 'database', 'fields', 'api', 'ui', 'admin', 'builder', 'wordpress'];
$layers = ['Unit', 'Integration', 'WordPress', 'Performance', 'Security'];

foreach ($packages as $package) {
    foreach ($layers as $layer) {
        $dir = $root . '/' . $package . '/' . $layer;
        if (! is_dir($dir)) {
            continue;
        }
        foreach (glob($dir . '/*.php') ?: [] as $file) {
            unlink($file);
        }
        @rmdir($dir);
        echo "cleaned {$package}/{$layer}\n";
    }
}
