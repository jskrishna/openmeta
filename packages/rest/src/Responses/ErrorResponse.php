<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Responses;

/**
 * JSON error envelope: { error: { message, code, details? } }.
 */
final class ErrorResponse extends Response
{
    /**
     * @param array<string, mixed> $details
     * @param array<string, string> $headers
     */
    public function __construct(
        string $message,
        string $code = 'error',
        int $status = 400,
        private readonly array $details = [],
        array $headers = ['Content-Type' => 'application/json'],
    ) {
        $error = ['message' => $message, 'code' => $code];

        if ($details !== []) {
            $error['details'] = $details;
        }

        parent::__construct($status, ['error' => $error], $headers);
    }

    /**
     * @param array<string, mixed> $details
     */
    public static function make(
        string $message,
        int $status = 400,
        string $code = 'error',
        array $details = [],
    ): self {
        return new self($message, $code, $status, $details);
    }

    public function toArray(): array
    {
        /** @var array{error?: array<string, mixed>} $content */
        $content = is_array($this->content()) ? $this->content() : [];

        return ['error' => $content['error'] ?? ['message' => 'Error', 'code' => 'error']];
    }
}
