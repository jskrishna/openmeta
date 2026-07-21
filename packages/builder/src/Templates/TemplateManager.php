<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Templates;

use OpenMeta\Core\Contracts\EventDispatcherInterface;
use OpenMeta\Builder\Canvas\Canvas;
use OpenMeta\Builder\Events\TemplateImported;

/**
 * Template manager — registry facade with import/export events.
 */
final class TemplateManager
{
    public function __construct(
        private readonly TemplateRegistry $registry,
        private readonly EventDispatcherInterface $events,
    ) {
    }

    public function registry(): TemplateRegistry
    {
        return $this->registry;
    }

    public function apply(string $id, Canvas $canvas): void
    {
        $this->registry->apply($id, $canvas);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function import(array $payload): void
    {
        $this->registry->import($payload);
        $this->events->dispatch(new TemplateImported(
            (string) ($payload['id'] ?? ''),
            $payload,
        ));
    }
}
