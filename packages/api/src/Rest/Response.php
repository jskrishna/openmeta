<?php

declare(strict_types=1);

namespace OpenMeta\Api\Rest;

/**
 * JSON HTTP response envelope.
 */
final class Response
{
    /**
     * @param array<string, mixed>|list<mixed>|null $data
     * @param array<string, mixed> $meta
     * @param array<string, string> $headers
     */
    public function __construct(
        private readonly int $status,
        private readonly mixed $data = null,
        private readonly array $meta = [],
        private readonly ?array $error = null,
        private readonly array $headers = ['Content-Type' => 'application/json'],
    ) {
    }

    /**
     * @param array<string, mixed>|list<mixed>|null $data
     * @param array<string, mixed> $meta
     */
    public static function json(mixed $data = null, int $status = 200, array $meta = []): self
    {
        return new self($status, $data, $meta);
    }

    /**
     * @param array{message: string, code: string, details?: mixed} $error
     */
    public static function error(array $error, int $status): self
    {
        return new self($status, null, [], $error);
    }

    public function status(): int
    {
        return $this->status;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        if ($this->error !== null) {
            return [
                'error' => $this->error,
            ];
        }

        $payload = ['data' => $this->data];

        if ($this->meta !== []) {
            $payload['meta'] = $this->meta;
        }

        return $payload;
    }

    public function body(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    /** @return array<string, string> */
    public function headers(): array
    {
        return $this->headers;
    }
}
