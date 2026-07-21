<?php

declare(strict_types=1);

namespace OpenMeta\Validation\Results;

/**
 * One structured validation failure.
 *
 * Shape: field (attribute), rule, message, context (params).
 */
final class ValidationError
{
    /**
     * @param array<string, string|int|float> $params Message / rule context
     */
    public function __construct(
        public readonly string $attribute,
        public readonly string $rule,
        public readonly string $message,
        public readonly string $code,
        public readonly array $params = [],
    ) {
    }

    /** Field name under validation (alias of {@see $attribute}). */
    public function field(): string
    {
        return $this->attribute;
    }

    /**
     * Rule context / placeholder values (alias of {@see $params}).
     *
     * @return array<string, string|int|float>
     */
    public function context(): array
    {
        return $this->params;
    }

    /**
     * @return array{
     *     attribute: string,
     *     field: string,
     *     rule: string,
     *     message: string,
     *     code: string,
     *     params: array<string, string|int|float>,
     *     context: array<string, string|int|float>
     * }
     */
    public function toArray(): array
    {
        return [
            'attribute' => $this->attribute,
            'field' => $this->attribute,
            'rule' => $this->rule,
            'message' => $this->message,
            'code' => $this->code,
            'params' => $this->params,
            'context' => $this->params,
        ];
    }
}
