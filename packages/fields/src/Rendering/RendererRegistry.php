<?php

declare(strict_types=1);

namespace OpenMeta\Fields\Rendering;

use OpenMeta\Fields\Contracts\FieldRendererInterface;
use OpenMeta\Fields\Exceptions\InvalidFieldException;

/**
 * Pluggable renderer catalog — Admin / UI register concrete renderers here.
 */
final class RendererRegistry
{
    /** @var array<string, FieldRendererInterface> */
    private array $renderers = [];

    public function register(string $name, FieldRendererInterface $renderer): void
    {
        $this->renderers[$name] = $renderer;
    }

    public function has(string $name): bool
    {
        return isset($this->renderers[$name]);
    }

    public function get(string $name): FieldRendererInterface
    {
        if (! isset($this->renderers[$name])) {
            throw new InvalidFieldException(sprintf('Unknown renderer [%s].', $name));
        }

        return $this->renderers[$name];
    }
}
