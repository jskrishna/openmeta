<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Tables;

use OpenMeta\Admin\Events\TableLoaded;
use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Database\Pagination\LengthAwarePaginator;
use OpenMeta\Security\Escaping\Escaper;
use OpenMeta\Ui\Components\DataTable;

/**
 * Data table framework — columns, sort, search, pagination, actions.
 */
final class TableBuilder
{
    /** @var array<string, string> */
    private array $columns = [];

    /** @var list<array<string, string>> */
    private array $rows = [];

    /** @var list<array{label: string, action: string}> */
    private array $bulkActions = [];

    /** @var list<array{label: string, action: string}> */
    private array $rowActions = [];

    private string $searchQuery = '';

    private ?string $sortColumn = null;

    private string $sortDirection = 'asc';

    private ?LengthAwarePaginator $paginator = null;

    public function __construct(
        private readonly string $id,
        private readonly ?EventDispatcherInterface $events = null,
    ) {
    }

    public function column(string $key, string $label): self
    {
        $this->columns[$key] = $label;

        return $this;
    }

    /**
     * @param list<array<string, string>> $rows
     */
    public function rows(array $rows): self
    {
        $this->rows = $rows;

        return $this;
    }

    public function paginator(LengthAwarePaginator $paginator): self
    {
        $this->paginator = $paginator;

        return $this;
    }

    public function search(string $query): self
    {
        $this->searchQuery = mb_strtolower(trim($query));

        return $this;
    }

    public function sort(string $column, string $direction = 'asc'): self
    {
        $this->sortColumn = $column;
        $this->sortDirection = strtolower($direction) === 'desc' ? 'desc' : 'asc';

        return $this;
    }

    public function bulkAction(string $label, string $action): self
    {
        $this->bulkActions[] = ['label' => $label, 'action' => $action];

        return $this;
    }

    public function rowAction(string $label, string $action): self
    {
        $this->rowActions[] = ['label' => $label, 'action' => $action];

        return $this;
    }

    public function render(): string
    {
        $rows = $this->filteredRows();
        $rows = $this->sortedRows($rows);
        $headers = array_values($this->columns);

        if ($this->rowActions !== []) {
            $headers[] = 'Actions';
        }

        if ($this->paginator !== null) {
            $matrix = $this->matrixFromPaginator();
            $total = $this->paginator->total();
            $page = $this->paginator->currentPage();
        } else {
            $matrix = $this->toMatrix($rows);
            $total = count($matrix);
            $page = 1;
        }

        $this->events?->dispatch(new TableLoaded($this->id, $total, $page));

        $html = $this->renderToolbar();
        $html .= DataTable::render($headers, $matrix);

        if ($this->paginator !== null) {
            $html .= '<div class="om-table__pagination">'
                . Escaper::html(sprintf(
                    'Page %d of %d (%d items)',
                    $this->paginator->currentPage(),
                    $this->paginator->lastPage(),
                    $this->paginator->total()
                ))
                . '</div>';
        }

        return $html;
    }

    private function renderToolbar(): string
    {
        if ($this->bulkActions === [] && $this->searchQuery === '') {
            return '';
        }

        $html = '<div class="om-table__toolbar">';

        foreach ($this->bulkActions as $action) {
            $html .= '<button type="button" data-bulk-action="'
                . Escaper::attr($action['action']) . '">'
                . Escaper::html($action['label']) . '</button>';
        }

        if ($this->searchQuery !== '') {
            $html .= '<span class="om-table__search">Search: ' . Escaper::html($this->searchQuery) . '</span>';
        }

        return $html . '</div>';
    }

    /**
     * @return list<array<string, string>>
     */
    private function filteredRows(): array
    {
        if ($this->searchQuery === '') {
            return $this->rows;
        }

        return array_values(array_filter(
            $this->rows,
            function (array $row): bool {
                foreach ($row as $value) {
                    if (str_contains(mb_strtolower($value), $this->searchQuery)) {
                        return true;
                    }
                }

                return false;
            }
        ));
    }

    /**
     * @param list<array<string, string>> $rows
     * @return list<array<string, string>>
     */
    private function sortedRows(array $rows): array
    {
        if ($this->sortColumn === null) {
            return $rows;
        }

        $column = $this->sortColumn;
        usort(
            $rows,
            function (array $a, array $b) use ($column): int {
                $left = $a[$column] ?? '';
                $right = $b[$column] ?? '';
                $cmp = strcmp($left, $right);

                return $this->sortDirection === 'desc' ? -$cmp : $cmp;
            }
        );

        return $rows;
    }

    /**
     * @param list<array<string, string>> $rows
     * @return list<list<string>>
     */
    private function toMatrix(array $rows): array
    {
        $keys = array_keys($this->columns);
        $matrix = [];

        foreach ($rows as $row) {
            $line = [];

            foreach ($keys as $key) {
                $line[] = $row[$key] ?? '';
            }

            if ($this->rowActions !== []) {
                $actions = [];

                foreach ($this->rowActions as $action) {
                    $actions[] = $action['label'];
                }

                $line[] = implode(', ', $actions);
            }

            $matrix[] = $line;
        }

        return $matrix;
    }

    /**
     * @return list<list<string>>
     */
    private function matrixFromPaginator(): array
    {
        $keys = array_keys($this->columns);
        $matrix = [];

        foreach ($this->paginator?->items()->all() ?? [] as $row) {
            $line = [];

            foreach ($keys as $key) {
                $line[] = (string) ($row[$key] ?? '');
            }

            if ($this->rowActions !== []) {
                $line[] = '';
            }

            $matrix[] = $line;
        }

        return $matrix;
    }
}
