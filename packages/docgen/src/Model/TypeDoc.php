<?php

declare(strict_types=1);

namespace OpenMeta\Docgen\Model;

/**
 * Documentation for one public type (class / interface / enum / trait).
 */
final class TypeDoc
{
    /**
     * @param list<MethodDoc> $methods
     * @param list<string>    $constants
     */
    public function __construct(
        public readonly string $fqcn,
        public readonly string $kind,
        public readonly string $summary,
        public readonly array $methods = [],
        public readonly array $constants = [],
    ) {
    }

    public function shortName(): string
    {
        $position = strrpos($this->fqcn, '\\');

        return $position === false ? $this->fqcn : substr($this->fqcn, $position + 1);
    }

    public function namespace(): string
    {
        $position = strrpos($this->fqcn, '\\');

        return $position === false ? '' : substr($this->fqcn, 0, $position);
    }
}
