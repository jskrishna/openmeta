<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Contracts;

/**
 * Resolves human-readable validation messages (localization-ready; catalogs later).
 */
interface MessageResolverInterface
{
    /**
     * @param array<string, string|int|float> $params
     */
    public function make(string $attribute, string $rule, string $defaultTemplate, array $params = []): string;
}
