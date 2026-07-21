<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Api;

use OpenMeta\Docgen\Model\TypeDoc;

/**
 * Renders scanned API types into Markdown reference pages.
 */
final class ApiDocRenderer
{
    /**
     * @param list<TypeDoc> $types
     */
    public function renderPackage(string $package, array $types): string
    {
        $lines = [];
        $lines[] = '# API Reference — `' . $package . '`';
        $lines[] = '';
        $lines[] = 'Auto-generated from public PHP types. Do not edit by hand.';
        $lines[] = '';

        if ($types === []) {
            $lines[] = '_No public types found._';

            return implode("\n", $lines) . "\n";
        }

        foreach ($types as $type) {
            $lines[] = $this->renderType($type);
        }

        return implode("\n", $lines) . "\n";
    }

    public function renderType(TypeDoc $type): string
    {
        $lines = [];
        $lines[] = '## ' . $type->shortName();
        $lines[] = '';
        $lines[] = '`' . $type->kind . ' ' . $type->fqcn . '`';
        $lines[] = '';

        if ($type->summary !== '') {
            $lines[] = $type->summary;
            $lines[] = '';
        }

        if ($type->constants !== []) {
            $lines[] = '**Constants:** ' . implode(', ', array_map(
                static fn (string $constant): string => '`' . $constant . '`',
                $type->constants,
            ));
            $lines[] = '';
        }

        if ($type->methods !== []) {
            $lines[] = '**Methods**';
            $lines[] = '';
            foreach ($type->methods as $method) {
                $summary = $method->summary === '' ? '' : ' — ' . $method->summary;
                $lines[] = '- `' . $method->signature . '`' . $summary;
            }
            $lines[] = '';
        }

        return implode("\n", $lines);
    }

    /**
     * @param array<string, int> $packageCounts package => type count
     */
    public function renderIndex(array $packageCounts): string
    {
        $lines = [];
        $lines[] = '# API Reference';
        $lines[] = '';
        $lines[] = 'Generated reference for every OpenMeta package.';
        $lines[] = '';
        $lines[] = '| Package | Public types |';
        $lines[] = '| ------- | ------------ |';

        ksort($packageCounts);

        foreach ($packageCounts as $package => $count) {
            $lines[] = sprintf('| [%s](./%s.md) | %d |', $package, $package, $count);
        }

        return implode("\n", $lines) . "\n";
    }
}
