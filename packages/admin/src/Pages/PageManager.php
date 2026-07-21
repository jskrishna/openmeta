<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Pages;

use OpenMeta\Admin\Contracts\PageManagerInterface;
use OpenMeta\Admin\Exceptions\AdminException;

/**
 * Page registration API (public façade over screens).
 */
final class PageManager implements PageManagerInterface
{
    /** @var array<string, Page> */
    private array $pages = [];

    public function register(Page $page): void
    {
        $this->pages[$page->id] = $page;
    }

    public function has(string $id): bool
    {
        return isset($this->pages[$id]);
    }

    public function get(string $id): Page
    {
        if (! isset($this->pages[$id])) {
            throw new AdminException(sprintf('Unknown page [%s].', $id));
        }

        return $this->pages[$id];
    }

    /** @return list<Page> */
    public function all(): array
    {
        return array_values($this->pages);
    }
}
