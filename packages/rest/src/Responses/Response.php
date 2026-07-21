<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Responses;

/**
 * Immutable HTTP response base.
 */
class Response
{
    /**
     * @param array<string, string> $headers
     * @param array<string, string> $cookies
     */
    public function __construct(
        private readonly int $status = 200,
        private readonly mixed $content = null,
        private readonly array $headers = [],
        private readonly array $cookies = [],
    ) {
    }

    public function status(): int
    {
        return $this->status;
    }

    public function content(): mixed
    {
        return $this->content;
    }

    /** @return array<string, string> */
    public function headers(): array
    {
        return $this->headers;
    }

    /** @return array<string, string> */
    public function cookies(): array
    {
        return $this->cookies;
    }

    public function header(string $name, ?string $default = null): ?string
    {
        $name = strtolower($name);

        foreach ($this->headers as $key => $value) {
            if (strtolower($key) === $name) {
                return $value;
            }
        }

        return $default;
    }

    /** @param array<string, string> $headers */
    public function withHeaders(array $headers): Response
    {
        return new Response(
            $this->status,
            $this->content,
            array_merge($this->headers, $headers),
            $this->cookies,
        );
    }

    public function withStatus(int $status): Response
    {
        return new Response($status, $this->content, $this->headers, $this->cookies);
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        if (is_array($this->content)) {
            return $this->content;
        }

        return ['data' => $this->content];
    }

    public function body(): string
    {
        if (is_string($this->content)) {
            return $this->content;
        }

        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }
}
