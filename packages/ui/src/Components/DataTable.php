<?php

declare(strict_types=1);

namespace OpenMeta\Ui\Components;

use OpenMeta\Security\Escaping\Escaper;

/**
 * Simple data table component (presentation only).
 */
final class DataTable
{
    /**
     * @param list<string> $headers
     * @param list<list<string>> $rows
     */
    public static function render(array $headers, array $rows, bool $emptyMessage = true): string
    {
        $html = '<table class="om-table"><thead><tr>';

        foreach ($headers as $header) {
            $html .= '<th scope="col">' . Escaper::html($header) . '</th>';
        }

        $html .= '</tr></thead><tbody>';

        if ($rows === []) {
            $cols = max(1, count($headers));
            $message = $emptyMessage ? 'No items found.' : '';
            $html .= '<tr><td colspan="' . $cols . '">' . Escaper::html($message) . '</td></tr>';
        } else {
            foreach ($rows as $row) {
                $html .= '<tr>';
                foreach ($row as $cell) {
                    $html .= '<td>' . Escaper::html($cell) . '</td>';
                }
                $html .= '</tr>';
            }
        }

        return $html . '</tbody></table>';
    }
}
