<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Contracts;

/**
 * Renders a stub template with variables, conditional blocks, and placeholders.
 */
interface TemplateEngineInterface
{
    /**
     * @param array<string, string> $variables
     */
    public function render(string $template, array $variables): string;
}
