<?php

declare(strict_types=1);

/**
 * Enforces a minimum line-coverage threshold from a Clover report.
 *
 * Usage: php quality/tools/coverage-check.php <clover.xml> <threshold>
 * Exit:  0 when coverage >= threshold, 1 otherwise.
 */

$cloverPath = $argv[1] ?? 'quality/coverage/clover.xml';
$threshold = (float) ($argv[2] ?? '70');

if (! is_file($cloverPath)) {
    fwrite(STDERR, "Coverage report [{$cloverPath}] not found.\n");
    exit(1);
}

$xml = simplexml_load_file($cloverPath);

if ($xml === false) {
    fwrite(STDERR, "Unable to parse coverage report [{$cloverPath}].\n");
    exit(1);
}

$metrics = $xml->project->metrics ?? null;

if ($metrics === null) {
    fwrite(STDERR, "No metrics found in coverage report.\n");
    exit(1);
}

$elements = (int) $metrics['elements'];
$covered = (int) $metrics['coveredelements'];
$coverage = $elements > 0 ? ($covered / $elements) * 100 : 0.0;

printf("Coverage: %.2f%% (%d/%d elements) — threshold %.2f%%\n", $coverage, $covered, $elements, $threshold);

if ($coverage + 1e-9 < $threshold) {
    fwrite(STDERR, "Coverage below threshold.\n");
    exit(1);
}

echo "Coverage threshold met.\n";
exit(0);
