<?php

declare(strict_types=1);

namespace OpenMeta\Sdk\Manifest;

/**
 * Environment requirements declared by an extension.
 *
 * A null PHP/WordPress constraint means "no requirement".
 */
final class Requirements
{
    /**
     * @param list<string> $phpExtensions Required PHP extensions (e.g. "json", "mbstring")
     */
    public function __construct(
        public readonly ?string $php = null,
        public readonly ?string $wordpress = null,
        public readonly array $phpExtensions = [],
    ) {
    }

    public function requiresWordpress(): bool
    {
        return $this->wordpress !== null;
    }

    /**
     * @return array{php: string|null, wordpress: string|null, phpExtensions: list<string>}
     */
    public function toArray(): array
    {
        return [
            'php' => $this->php,
            'wordpress' => $this->wordpress,
            'phpExtensions' => $this->phpExtensions,
        ];
    }
}
