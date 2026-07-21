<?php

declare(strict_types=1);

namespace OpenMeta\Rest\Resources;

use OpenMeta\Rest\Contracts\ResourceInterface;

/**
 * Structured error payload for REST responses.
 */
final class ErrorResource implements ResourceInterface
{
    /**
     * @param array<string, mixed> $details
     */
    public function __construct(
        private readonly string $message,
        private readonly string $code = 'error',
        private readonly array $details = [],
    ) {
    }

    public function toArray(): array
    {
        $payload = [
            'message' => $this->message,
            'code' => $this->code,
        ];

        if ($this->details !== []) {
            $payload['details'] = $this->details;
        }

        return $payload;
    }
}
