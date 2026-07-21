<?php

declare(strict_types=1);

namespace OpenMeta\Cli\Output;

/**
 * Renders a simple bordered ASCII table.
 */
final class TableRenderer
{
    /**
     * @param list<string>       $headers
     * @param list<list<string>> $rows
     */
    public function render(array $headers, array $rows): string
    {
        $widths = $this->columnWidths($headers, $rows);
        $lines = [];

        $lines[] = $this->separator($widths);
        $lines[] = $this->row($headers, $widths);
        $lines[] = $this->separator($widths);

        foreach ($rows as $row) {
            $lines[] = $this->row($row, $widths);
        }

        $lines[] = $this->separator($widths);

        return implode("\n", $lines);
    }

    /**
     * @param list<string>       $headers
     * @param list<list<string>> $rows
     *
     * @return list<int>
     */
    private function columnWidths(array $headers, array $rows): array
    {
        $widths = array_map('strlen', $headers);

        foreach ($rows as $row) {
            foreach ($row as $column => $value) {
                $length = strlen($value);
                $widths[$column] = max($widths[$column] ?? 0, $length);
            }
        }

        return array_values($widths);
    }

    /**
     * @param list<string> $cells
     * @param list<int>    $widths
     */
    private function row(array $cells, array $widths): string
    {
        $rendered = [];

        foreach ($widths as $column => $width) {
            $rendered[] = str_pad($cells[$column] ?? '', $width);
        }

        return '| ' . implode(' | ', $rendered) . ' |';
    }

    /**
     * @param list<int> $widths
     */
    private function separator(array $widths): string
    {
        $segments = array_map(static fn (int $width): string => str_repeat('-', $width + 2), $widths);

        return '+' . implode('+', $segments) . '+';
    }
}
