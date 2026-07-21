<?php

declare(strict_types=1);

namespace OpenMeta\Generator\Templates;

use OpenMeta\Generator\Contracts\TemplateEngineInterface;

/**
 * A small, dependency-free stub template engine.
 *
 * Supports:
 *   - placeholders:        {{ name }}  /  {{name}}
 *   - conditional blocks:  {{#if flag}}…{{/if}} and {{#unless flag}}…{{/unless}}
 *
 * A value is "truthy" unless it is '', '0', or 'false'. Unknown placeholders
 * render as an empty string so stubs never leak `{{ … }}` tokens.
 */
final class TemplateEngine implements TemplateEngineInterface
{
    public function render(string $template, array $variables): string
    {
        $rendered = $this->conditionals($template, $variables);

        return $this->placeholders($rendered, $variables);
    }

    /**
     * @param array<string, string> $variables
     */
    private function conditionals(string $template, array $variables): string
    {
        $ifResolved = preg_replace_callback(
            '/\{\{#if\s+(\w+)\}\}(.*?)\{\{\/if\}\}/s',
            fn (array $matches): string => $this->truthy($variables[$matches[1]] ?? '') ? $matches[2] : '',
            $template,
        ) ?? $template;

        return preg_replace_callback(
            '/\{\{#unless\s+(\w+)\}\}(.*?)\{\{\/unless\}\}/s',
            fn (array $matches): string => $this->truthy($variables[$matches[1]] ?? '') ? '' : $matches[2],
            $ifResolved,
        ) ?? $ifResolved;
    }

    /**
     * @param array<string, string> $variables
     */
    private function placeholders(string $template, array $variables): string
    {
        return preg_replace_callback(
            '/\{\{\s*(\w+)\s*\}\}/',
            static fn (array $matches): string => $variables[$matches[1]] ?? '',
            $template,
        ) ?? $template;
    }

    private function truthy(string $value): bool
    {
        return $value !== '' && $value !== '0' && strtolower($value) !== 'false';
    }
}
