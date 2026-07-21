<?php

declare(strict_types=1);

namespace OpenMeta\Builder\Events;

final class TemplateImported
{
    /**
     * @param array<string, mixed> $template
     */
    public function __construct(
        public readonly string $templateId,
        public readonly array $template,
    ) {
    }
}
