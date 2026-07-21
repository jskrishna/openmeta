<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Groups;

use OpenMeta\Fields\Contracts\FieldGroupInterface;
use OpenMeta\Fields\Exceptions\InvalidFieldException;

/**
 * DI-friendly catalog of field groups (no static registry).
 */
final class FieldGroupRegistry
{
    /** @var array<string, FieldGroupInterface> */
    private array $groups = [];

    public function register(FieldGroupInterface $group): void
    {
        $this->groups[$group->id()] = $group;
    }

    public function remove(string $id): void
    {
        unset($this->groups[$id]);
    }

    public function has(string $id): bool
    {
        return isset($this->groups[$id]);
    }

    public function get(string $id): FieldGroupInterface
    {
        if (! isset($this->groups[$id])) {
            throw new InvalidFieldException(sprintf('Unknown field group [%s].', $id));
        }

        return $this->groups[$id];
    }

    /**
     * @return list<FieldGroupInterface>
     */
    public function all(): array
    {
        $groups = array_values($this->groups);
        usort(
            $groups,
            static fn (FieldGroupInterface $a, FieldGroupInterface $b): int => $a->order() <=> $b->order()
        );

        return $groups;
    }
}
