<?php

declare(strict_types=1);

$layers = ['Unit', 'Integration', 'WordPress', 'Performance', 'Security'];
$root = dirname(__DIR__) . '/packages';
$fixed = 0;

foreach (glob($root . '/*', GLOB_ONLYDIR) ?: [] as $pkg) {
    foreach ($layers as $layer) {
        foreach (glob($pkg . '/tests/' . $layer . '/*Test.php') ?: [] as $file) {
            $contents = file_get_contents($file);
            if ($contents === false) {
                continue;
            }
            if (! str_ends_with($contents, "\n")) {
                file_put_contents($file, $contents . "\n");
                $fixed++;
            }
        }
    }
}

echo "fixed {$fixed} files\n";
