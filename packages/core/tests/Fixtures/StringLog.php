<?php

declare(strict_types=1);

namespace OpenMeta\Core\Tests\Fixtures;

/**
 * Mutable list used by anonymous providers in tests.
 */
final class StringLog
{
    /** @var list<string> */
    private array $items = [];

    public function add(string $item): void
    {
        $this->items[] = $item;
    }

    /** @return list<string> */
    public function all(): array
    {
        return $this->items;
    }
}
