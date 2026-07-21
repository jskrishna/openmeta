<?php

declare(strict_types=1);

/** Patch SPEC/README Status lines to Phase 13 versions. */

$updates = [
    'packages/support/SPEC.md' => ['0.2.0-alpha', 'Phase 3 / `v0.2.0-alpha`'],
    'packages/support/README.md' => null,
    'packages/validation/SPEC.md' => '✅ Complete — Phase 4 / `v0.3.0-alpha`',
    'packages/validation/README.md' => null,
    'packages/security/SPEC.md' => '✅ Complete — Phase 5 / `v0.4.0-alpha`',
    'packages/security/README.md' => null,
    'packages/database/SPEC.md' => '✅ Complete — Phase 6 / `v0.5.0-alpha`',
    'packages/database/README.md' => '**Status:** ✅ Complete (Phase 6) · **v0.5.0-alpha**',
    'packages/fields/SPEC.md' => '✅ Complete — Phase 7 / `v0.6.0-alpha`',
    'packages/fields/README.md' => '**Status:** ✅ Complete (Phase 7) · **v0.6.0-alpha**',
    'packages/api/SPEC.md' => '✅ Complete — Phase 8 / `v0.7.0-alpha`',
    'packages/api/README.md' => '**Status:** ✅ Complete (Phase 8) · **v0.7.0-alpha**',
    'packages/ui/SPEC.md' => '✅ Complete — Phase 9 / `v0.8.0-alpha` (PHP components; React surface later)',
    'packages/ui/README.md' => '**Status:** ✅ Complete (Phase 9 / `v0.8.0-alpha`) · PHP view components (React kit later)',
    'packages/admin/SPEC.md' => '✅ Complete — Phase 9 / `v0.8.0-alpha`',
    'packages/admin/README.md' => '**Status:** ✅ Complete (Phase 9) · **v0.8.0-alpha**',
    'packages/builder/SPEC.md' => '✅ Complete — Phase 10 / `v0.9.0-beta`',
    'packages/builder/README.md' => '**Status:** ✅ Complete (Phase 10) · **v0.9.0-beta**',
    'packages/wordpress/SPEC.md' => '✅ Complete — Phase 11 / `v0.9.0-beta`',
    'packages/wordpress/README.md' => '**Status:** ✅ Complete (Phase 11) · **v0.9.0-beta**',
];

$root = dirname(__DIR__);

$replacements = [
    ['**Status:** ✅ Complete — Phase 6 / `v0.3.0-alpha`', '**Status:** ✅ Complete — Phase 6 / `v0.5.0-alpha`'],
    ['**Status:** ✅ Complete (Phase 6) · **v0.3.0-alpha**', '**Status:** ✅ Complete (Phase 6) · **v0.5.0-alpha**'],
    ['**Status:** ✅ Complete — Phase 7 / `v0.4.0-alpha`', '**Status:** ✅ Complete — Phase 7 / `v0.6.0-alpha`'],
    ['**Status:** ✅ Complete (Phase 7) · **v0.4.0-alpha**', '**Status:** ✅ Complete (Phase 7) · **v0.6.0-alpha**'],
    ['**Status:** ✅ Complete — Phase 8 / `v0.5.0-alpha`', '**Status:** ✅ Complete — Phase 8 / `v0.7.0-alpha`'],
    ['**Status:** ✅ Complete (Phase 8) · **v0.5.0-alpha**', '**Status:** ✅ Complete (Phase 8) · **v0.7.0-alpha**'],
    ['**Status:** ✅ Complete — Phase 9 / `v0.6.0-alpha` (PHP components; React surface later)', '**Status:** ✅ Complete — Phase 9 / `v0.8.0-alpha` (PHP components; React surface later)'],
    ['**Status:** ✅ Complete (Phase 9 / `v0.6.0-alpha`) · PHP view components (React kit later)', '**Status:** ✅ Complete (Phase 9 / `v0.8.0-alpha`) · PHP view components (React kit later)'],
    ['**Status:** ✅ Complete — Phase 9 / `v0.6.0-alpha`', '**Status:** ✅ Complete — Phase 9 / `v0.8.0-alpha`'],
    ['**Status:** ✅ Complete (Phase 9) · **v0.6.0-alpha**', '**Status:** ✅ Complete (Phase 9) · **v0.8.0-alpha**'],
    ['**Status:** ✅ Complete — Phase 10 / `v0.7.0-alpha`', '**Status:** ✅ Complete — Phase 10 / `v0.9.0-beta`'],
    ['**Status:** ✅ Complete (Phase 10) · **v0.7.0-alpha**', '**Status:** ✅ Complete (Phase 10) · **v0.9.0-beta**'],
    ['**Status:** ✅ Complete — Phase 11 / `v0.8.0-beta`', '**Status:** ✅ Complete — Phase 11 / `v0.9.0-beta`'],
    ['**Status:** ✅ Complete (Phase 11) · **v0.8.0-beta**', '**Status:** ✅ Complete (Phase 11) · **v0.9.0-beta**'],
    ['| 11 | **Wordpress** | ✅ `v0.8.0-beta` |', '| 11 | **Wordpress** | ✅ `v0.9.0-beta` |'],
];

$files = [
    'packages/database/SPEC.md',
    'packages/database/README.md',
    'packages/fields/SPEC.md',
    'packages/fields/README.md',
    'packages/api/SPEC.md',
    'packages/api/README.md',
    'packages/ui/SPEC.md',
    'packages/ui/README.md',
    'packages/admin/SPEC.md',
    'packages/admin/README.md',
    'packages/builder/SPEC.md',
    'packages/builder/README.md',
    'packages/wordpress/SPEC.md',
    'packages/wordpress/README.md',
    'packages/core/docs/build-order.md',
    'docs/roadmap/phase-10-visual-builder.md',
    'docs/roadmap/phase-11-wordpress-integration.md',
    'docs/roadmap/phase-09-admin.md',
    'docs/roadmap/phase-12-testing.md',
];

foreach ($files as $rel) {
    $path = $root . '/' . $rel;
    if (! is_readable($path)) {
        continue;
    }
    $contents = (string) file_get_contents($path);
    $original = $contents;
    foreach ($replacements as [$from, $to]) {
        $contents = str_replace($from, $to, $contents);
    }
    // Phase doc version scopes
    $contents = str_replace('(`v0.7.0-alpha`)', '(`v0.9.0-beta`)', $contents);
    $contents = str_replace('(`v0.8.0-beta`)', '(`v0.9.0-beta`)', $contents);
    $contents = str_replace('(`v0.6.0-alpha`)', '(`v0.8.0-alpha`)', $contents);
    $contents = str_replace('`v0.9.0-rc` gate', '`v1.0.0` gate', $contents);
    $contents = str_replace('**v0.9.0-rc** — Release Candidate (freeze features; soak Phase 12 gates).', '**v1.0.0** — Stable.', $contents);
    $contents = str_replace('**v0.9.0-rc** — Release Candidate.', '**v1.0.0** — Stable.', $contents);
    $contents = str_replace('**v0.9.0-rc** — Release Candidate (freeze features; bug/perf/security pass).', '**v1.0.0** — Stable.', $contents);
    $contents = str_replace('**v0.8.0-beta** — Feature Complete.', '**v0.9.0-beta** — Builder.', $contents);
    if ($contents !== $original) {
        file_put_contents($path, $contents);
        echo "updated $rel\n";
    }
}

// validation / security status if they still say v0.2
foreach (['packages/validation/SPEC.md', 'packages/validation/README.md', 'packages/security/SPEC.md', 'packages/security/README.md', 'packages/support/SPEC.md', 'packages/support/README.md'] as $rel) {
    $path = $root . '/' . $rel;
    if (! is_readable($path)) {
        continue;
    }
    $contents = (string) file_get_contents($path);
    $original = $contents;
    $contents = str_replace('`v0.2.0-alpha`', match (true) {
        str_contains($rel, 'validation') => '`v0.3.0-alpha`',
        str_contains($rel, 'security') => '`v0.4.0-alpha`',
        default => '`v0.2.0-alpha`',
    }, $contents);
    $contents = str_replace('**v0.2.0-alpha**', match (true) {
        str_contains($rel, 'validation') => '**v0.3.0-alpha**',
        str_contains($rel, 'security') => '**v0.4.0-alpha**',
        default => '**v0.2.0-alpha**',
    }, $contents);
    if ($contents !== $original) {
        file_put_contents($path, $contents);
        echo "updated $rel\n";
    }
}

echo "done\n";
