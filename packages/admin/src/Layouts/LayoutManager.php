<?php

declare(strict_types=1);

namespace OpenMeta\Admin\Layouts;

use OpenMeta\Admin\Contracts\LayoutInterface;
use OpenMeta\Admin\Contracts\LayoutManagerInterface;
use OpenMeta\Admin\Exceptions\AdminException;
use OpenMeta\Admin\Support\ScreenContext;

/**
 * Registry of composable admin layouts.
 */
final class LayoutManager implements LayoutManagerInterface
{
    /** @var array<string, LayoutInterface> */
    private array $layouts = [];

    public function __construct()
    {
        $this->register(new FullWidthLayout());
        $this->register(new SidebarLayout());
        $this->register(new SplitLayout());
        $this->register(new DashboardGridLayout());
        $this->register(new WizardLayout());
    }

    public function register(LayoutInterface $layout): void
    {
        $this->layouts[$layout->id()] = $layout;
    }

    public function has(string $layoutId): bool
    {
        return isset($this->layouts[$layoutId]);
    }

    public function render(string $layoutId, ScreenContext $context): string
    {
        if (! isset($this->layouts[$layoutId])) {
            throw new AdminException(sprintf('Unknown layout [%s].', $layoutId));
        }

        return $this->layouts[$layoutId]->render($context);
    }
}
