<?php

declare(strict_types=1);

/**
 * Backward-compatibility checker for OpenMeta's public API.
 *
 * Builds a signature map of every public class/interface/enum/trait and its
 * public methods (name → required-parameter count) and public constants across
 * all package `src/` trees, then compares it to a committed baseline.
 *
 * A break = a removed type, a removed public method/constant, or a public
 * method that now requires more parameters than before.
 *
 * Usage:
 *   php quality/tools/bc-check.php            compare against the baseline
 *   php quality/tools/bc-check.php --update   (re)write the baseline
 *
 * Exit: 0 when compatible (or baseline written), 1 on a detected break.
 */

require __DIR__ . '/../../vendor/autoload.php';

$root = dirname(__DIR__, 2);
$baselinePath = $root . '/quality/reports/api-baseline.json';
$update = in_array('--update', array_slice($argv, 1), true);

/** @var array<string, array<string, mixed>> $current */
$current = buildApiMap($root);

if ($update || ! is_file($baselinePath)) {
    if (! is_dir(dirname($baselinePath))) {
        mkdir(dirname($baselinePath), 0755, true);
    }

    ksort($current);
    file_put_contents($baselinePath, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
    printf("API baseline written: %d public types → %s\n", count($current), $baselinePath);
    exit(0);
}

/** @var array<string, array<string, mixed>> $baseline */
$baseline = json_decode((string) file_get_contents($baselinePath), true) ?: [];
$breaks = compareApi($baseline, $current);

if ($breaks === []) {
    printf("Backward compatible: %d public types checked, no breaks.\n", count($baseline));
    exit(0);
}

fwrite(STDERR, "Backward-compatibility breaks detected:\n");
foreach ($breaks as $break) {
    fwrite(STDERR, '  - ' . $break . "\n");
}
exit(1);

/**
 * @return array<string, array<string, mixed>>
 */
function buildApiMap(string $root): array
{
    $composer = json_decode((string) file_get_contents($root . '/composer.json'), true);
    $psr4 = $composer['autoload']['psr-4'] ?? [];

    $map = [];

    foreach ($psr4 as $prefix => $dir) {
        $base = $root . '/' . rtrim($dir, '/');

        if (! is_dir($base)) {
            continue;
        }

        foreach (phpFiles($base) as $file) {
            $fqcn = fqcnFor($file);

            if ($fqcn === null || ! typeExists($fqcn)) {
                continue;
            }

            $map[$fqcn] = reflectType($fqcn);
        }
    }

    return $map;
}

/**
 * @return list<string>
 */
function phpFiles(string $dir): array
{
    $files = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));

    foreach ($iterator as $file) {
        if ($file instanceof SplFileInfo && $file->isFile() && $file->getExtension() === 'php') {
            $files[] = $file->getPathname();
        }
    }

    return $files;
}

function fqcnFor(string $file): ?string
{
    $code = (string) file_get_contents($file);

    if (
        preg_match('/namespace\s+([^;]+);/', $code, $ns) !== 1
        || preg_match('/(?:final\s+|abstract\s+|readonly\s+)*(?:class|interface|trait|enum)\s+(\w+)/', $code, $type) !== 1
    ) {
        return null;
    }

    return trim($ns[1]) . '\\' . $type[1];
}

function typeExists(string $fqcn): bool
{
    return class_exists($fqcn) || interface_exists($fqcn) || trait_exists($fqcn) || enum_exists($fqcn);
}

/**
 * @return array<string, mixed>
 */
function reflectType(string $fqcn): array
{
    $reflection = new ReflectionClass($fqcn);
    $methods = [];

    foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
        if ($method->getDeclaringClass()->getName() !== $fqcn) {
            continue;
        }

        $methods[$method->getName()] = $method->getNumberOfRequiredParameters();
    }

    ksort($methods);
    $constants = array_keys($reflection->getConstants());
    sort($constants);

    return ['methods' => $methods, 'constants' => $constants];
}

/**
 * @param array<string, array<string, mixed>> $baseline
 * @param array<string, array<string, mixed>> $current
 *
 * @return list<string>
 */
function compareApi(array $baseline, array $current): array
{
    $breaks = [];

    foreach ($baseline as $type => $api) {
        if (! isset($current[$type])) {
            $breaks[] = "removed type: {$type}";
            continue;
        }

        $oldMethods = is_array($api['methods'] ?? null) ? $api['methods'] : [];
        $newMethods = is_array($current[$type]['methods'] ?? null) ? $current[$type]['methods'] : [];

        foreach ($oldMethods as $method => $required) {
            if (! array_key_exists($method, $newMethods)) {
                $breaks[] = "removed method: {$type}::{$method}()";
            } elseif ((int) $newMethods[$method] > (int) $required) {
                $breaks[] = "stricter signature: {$type}::{$method}() now requires more arguments";
            }
        }

        $oldConstants = is_array($api['constants'] ?? null) ? $api['constants'] : [];
        $newConstants = is_array($current[$type]['constants'] ?? null) ? $current[$type]['constants'] : [];

        foreach (array_diff($oldConstants, $newConstants) as $constant) {
            $breaks[] = "removed constant: {$type}::{$constant}";
        }
    }

    return $breaks;
}
