<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Responses;

/**
 * JSON response envelope: { data, meta? }.
 */
final class JsonResponse extends Response
{
    /**
     * @param array<string, mixed>|list<mixed>|null $data
     * @param array<string, mixed> $meta
     * @param array<string, string> $headers
     */
    public function __construct(
        mixed $data = null,
        int $status = 200,
        private readonly array $meta = [],
        array $headers = ['Content-Type' => 'application/json'],
    ) {
        parent::__construct($status, ['data' => $data, 'meta' => $meta], $headers);
    }

    public static function make(mixed $data = null, int $status = 200, array $meta = []): self
    {
        return new self($data, $status, $meta);
    }

    public function toArray(): array
    {
        /** @var array{data?: mixed, meta?: array<string, mixed>} $content */
        $content = is_array($this->content()) ? $this->content() : [];
        $payload = ['data' => $content['data'] ?? null];
        $meta = $content['meta'] ?? [];

        if ($meta !== []) {
            $payload['meta'] = $meta;
        }

        return $payload;
    }
}
