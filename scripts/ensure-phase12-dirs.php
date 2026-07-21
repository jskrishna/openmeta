<?php

declare(strict_types=1);

$packages = ['core', 'support', 'validation', 'security', 'database', 'fields', 'api', 'ui', 'admin', 'builder', 'wordpress'];
$layers = ['Unit', 'Integration', 'WordPress', 'Performance', 'Security'];
$root = dirname(__DIR__);

foreach ($packages as $package) {
    foreach ($layers as $layer) {
        $dir = $root . '/packages/' . $package . '/tests/' . $layer;
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }
}

echo "dirs ok\n";
