<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Screens;

use OpenMeta\Admin\Exceptions\AdminException;

/**
 * Screen registration API.
 */
final class ScreenRegistry
{
    /** @var array<string, Screen> */
    private array $screens = [];

    public function register(Screen $screen): void
    {
        $this->screens[$screen->id] = $screen;
    }

    public function has(string $id): bool
    {
        return isset($this->screens[$id]);
    }

    public function get(string $id): Screen
    {
        if (! isset($this->screens[$id])) {
            throw new AdminException(sprintf('Unknown screen [%s].', $id));
        }

        return $this->screens[$id];
    }

    /** @return list<Screen> */
    public function all(): array
    {
        return array_values($this->screens);
    }
}
