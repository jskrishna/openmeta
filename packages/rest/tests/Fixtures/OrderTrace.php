<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Tests\Fixtures;

final class OrderTrace
{
    /** @var list<string> */
    private array $order = [];

    public function push(string $label): void
    {
        $this->order[] = $label;
    }

    /** @return list<string> */
    public function all(): array
    {
        return $this->order;
    }
}
