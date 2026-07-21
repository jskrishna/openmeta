<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Tables;

use OpenMeta\Ui\Components\DataTable;

/**
 * Admin list table — paginated presentation over rows.
 */
final class AdminTable
{
    /**
     * @param list<string> $headers
     * @param list<list<string>> $rows
     */
    public function __construct(
        private readonly array $headers,
        private readonly array $rows,
        private readonly int $page = 1,
        private readonly int $perPage = 20,
    ) {
    }

    public function render(): string
    {
        $offset = max(0, ($this->page - 1) * $this->perPage);
        $slice = array_slice($this->rows, $offset, $this->perPage);

        return DataTable::render($this->headers, $slice);
    }

    public function total(): int
    {
        return count($this->rows);
    }

    public function page(): int
    {
        return $this->page;
    }

    public function perPage(): int
    {
        return $this->perPage;
    }
}
