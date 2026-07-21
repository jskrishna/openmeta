<?php

declare(strict_types=1);

/** Align package composer.json versions to Phase 13 release train. */

$map = [
    'core' => '0.1.0-alpha',
    'support' => '0.2.0-alpha',
    'validation' => '0.3.0-alpha',
    'security' => '0.4.0-alpha',
    'database' => '0.5.0-alpha',
    'fields' => '0.6.0-alpha',
    'api' => '0.7.0-alpha',
    'ui' => '0.8.0-alpha',
    'admin' => '0.8.0-alpha',
    'builder' => '0.9.0-beta',
    'wordpress' => '0.9.0-beta',
];

$root = dirname(__DIR__) . '/packages';

foreach ($map as $package => $version) {
    $path = $root . '/' . $package . '/composer.json';
    $json = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
    $json['version'] = $version;
    file_put_contents(
        $path,
        json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
    );
    echo "{$package} => {$version}\n";
}
