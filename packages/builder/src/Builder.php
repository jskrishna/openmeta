<?php

declare(strict_types=1);

namespace OpenMeta\Builder;

use OpenMeta\Builder\Application\BuilderApplication;
use OpenMeta\Builder\Canvas\Canvas;
use OpenMeta\Builder\Contracts\ComponentRegistryInterface;
use OpenMeta\Builder\Contracts\HistoryManagerInterface;
use OpenMeta\Builder\Contracts\SchemaManagerInterface;
use OpenMeta\Builder\Registry\ComponentRegistry;

/**
 * Public builder façade — hides implementation details.
 */
final class Builder
{
    public function __construct(private readonly BuilderApplication $application)
    {
    }

    public function application(): BuilderApplication
    {
        return $this->application;
    }

    public function canvas(): Canvas
    {
        return $this->application->canvas();
    }

    public function registry(): ComponentRegistryInterface
    {
        return $this->application->registry();
    }

    public function schema(): SchemaManagerInterface
    {
        return $this->application->schema();
    }

    public function history(): HistoryManagerInterface
    {
        return $this->application->history();
    }

    /** @return array<string, mixed> */
    public function sessionState(): array
    {
        return $this->application->sessionState();
    }
}
