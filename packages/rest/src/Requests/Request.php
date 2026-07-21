<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Requests;

/**
 * Immutable HTTP request — never bound to PHP superglobals.
 */
final class Request
{
    /**
     * @param array<string, string> $headers
     * @param array<string, mixed> $query
     * @param array<string, mixed> $json
     * @param array<string, mixed> $files
     * @param array<string, mixed> $cookies
     * @param array<string, mixed> $attributes
     */
    public function __construct(
        private readonly string $method,
        private readonly string $path,
        private readonly array $headers = [],
        private readonly array $query = [],
        private readonly array $json = [],
        private readonly array $files = [],
        private readonly array $cookies = [],
        private readonly array $attributes = [],
        private readonly mixed $user = null,
    ) {
    }

    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $body
     * @param array<string, mixed> $cookies
     * @param array<string, mixed> $files
     * @param array<string, string> $headers
     */
    public static function create(
        string $method,
        string $path,
        array $query = [],
        array $body = [],
        array $headers = [],
        array $cookies = [],
        array $files = [],
    ): self {
        return new self($method, $path, $headers, $query, $body, $files, $cookies);
    }

    public function method(): string
    {
        return strtoupper($this->method);
    }

    public function path(): string
    {
        return $this->path;
    }

    /** @return array<string, string> */
    public function headers(): array
    {
        return $this->headers;
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

    /** @return array<string, mixed> */
    public function query(): array
    {
        return $this->query;
    }

    public function queryParam(string $key, mixed $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }

    /** @return array<string, mixed> */
    public function json(): array
    {
        return $this->json;
    }

    /** @return array<string, mixed> */
    public function files(): array
    {
        return $this->files;
    }

    /** @return array<string, mixed> */
    public function cookies(): array
    {
        return $this->cookies;
    }

    /** @return array<string, mixed> */
    public function attributes(): array
    {
        return $this->attributes;
    }

    public function attribute(string $key, mixed $default = null): mixed
    {
        return $this->attributes[$key] ?? $default;
    }

    public function route(string $key, mixed $default = null): mixed
    {
        return $this->attribute($key, $default);
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->json[$key] ?? $this->query[$key] ?? $default;
    }

    /** @return array<string, mixed> */
    public function all(): array
    {
        return array_merge($this->query, $this->json);
    }

    public function user(): mixed
    {
        return $this->user;
    }

    /** @param array<string, mixed> $attributes */
    public function withAttributes(array $attributes): self
    {
        return new self(
            $this->method,
            $this->path,
            $this->headers,
            $this->query,
            $this->json,
            $this->files,
            $this->cookies,
            array_merge($this->attributes, $attributes),
            $this->user,
        );
    }

    public function withUser(mixed $user): self
    {
        return new self(
            $this->method,
            $this->path,
            $this->headers,
            $this->query,
            $this->json,
            $this->files,
            $this->cookies,
            $this->attributes,
            $user,
        );
    }

    /** @param array<string, string> $headers */
    public function withHeaders(array $headers): self
    {
        return new self(
            $this->method,
            $this->path,
            array_merge($this->headers, $headers),
            $this->query,
            $this->json,
            $this->files,
            $this->cookies,
            $this->attributes,
            $this->user,
        );
    }
}
