<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Messages;

use OpenMeta\Validation\Contracts\MessageResolverInterface;
use OpenMeta\Validation\Support\AttributeFormatter;

/**
 * Resolves human-readable messages from rule failures (localization-ready).
 */
final class MessageBag implements MessageResolverInterface
{
    /** @param array<string, string> $custom attribute.rule or rule => template */
    public function __construct(private array $custom = [])
    {
    }

    /**
     * @param array<string, string|int|float> $params
     */
    public function make(string $attribute, string $rule, string $defaultTemplate, array $params = []): string
    {
        $template = $this->custom[$attribute . '.' . $rule]
            ?? $this->custom[$rule]
            ?? $defaultTemplate;

        $replacements = [
            ':attribute' => AttributeFormatter::display($attribute),
            ':value' => $this->stringify($params['value'] ?? ''),
        ];

        foreach ($params as $key => $value) {
            $replacements[':' . $key] = $this->stringify($value);
        }

        return strtr($template, $replacements);
    }

    private function stringify(string|int|float $value): string
    {
        return (string) $value;
    }
}
