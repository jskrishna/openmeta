<?php

declare(strict_types=1);

/**
 * Release validation gate.
 *
 * Runs the full production-readiness checklist and prints a pass/fail summary.
 * Exit 0 only when every required check passes.
 *
 * Usage: composer release:validate   (or: php quality/tools/release-validate.php)
 */

$root = dirname(__DIR__, 2);
chdir($root);

/** @var list<array{label: string, command: string, required: bool}> $checks */
$checks = [
    ['label' => 'Composer validation', 'command' => 'composer validate --no-check-publish --strict', 'required' => true],
    ['label' => 'Static analysis (PHPStan)', 'command' => 'composer phpstan', 'required' => true],
    ['label' => 'Coding standards (PHPCS)', 'command' => 'composer phpcs', 'required' => true],
    ['label' => 'Test suite (PHPUnit)', 'command' => 'composer phpunit', 'required' => true],
    ['label' => 'Dependency audit', 'command' => 'composer audit --no-interaction', 'required' => false],
    ['label' => 'Backward compatibility', 'command' => 'php quality/tools/bc-check.php', 'required' => true],
];

$failures = 0;
$results = [];

foreach ($checks as $check) {
    fwrite(STDOUT, "→ {$check['label']}\n");
    $exitCode = 0;
    passthru($check['command'], $exitCode);

    $passed = $exitCode === 0;
    $results[] = ['label' => $check['label'], 'passed' => $passed, 'required' => $check['required']];

    if (! $passed && $check['required']) {
        $failures++;
    }
}

echo "\n" . str_repeat('=', 48) . "\n";
echo "Release validation summary\n";
echo str_repeat('=', 48) . "\n";

foreach ($results as $result) {
    $status = $result['passed'] ? 'PASS' : ($result['required'] ? 'FAIL' : 'WARN');
    printf("  [%-4s] %s\n", $status, $result['label']);
}

echo str_repeat('=', 48) . "\n";

if ($failures > 0) {
    fwrite(STDERR, "Release blocked: {$failures} required check(s) failed.\n");
    exit(1);
}

echo "Release validation passed.\n";
exit(0);
